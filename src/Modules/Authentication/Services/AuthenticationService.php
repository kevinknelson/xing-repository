<?php

    namespace Modules\Authentication\Services {
        use Xing\Mapping\IRepository;
        use Xing\System\DateTime\DateTime;
        use Xing\System\Locator;
        use Modules\Authentication\Helpers\HashHelper;
        use Modules\Authentication\Model\LoginFailure;
        use Modules\Authentication\Model\Search\LoginFailureSearch;
        use Modules\Authentication\Model\Search\UserSearch;
        use Modules\Authentication\Model\Session;
        use Modules\Authentication\Model\User;

        class AuthenticationService {
            const MaxAttemptsBeforeIpBackOff    = 5;
            /** @var IRepository */
            private $_repository;
            /** @var User|null */
            private $_user;
            /** @var string */
            private $_email;

            public function __construct( $email ) {
                $this->_repository      = Locator::get('IRepository');
                $this->_email           = $email;
                $this->_user            = $this->_repository->search(UserSearch::where()->Email->is($email))->firstOrDefault();
            }
            public function getHandshakeSalt() {
                $emailSalt      = HashHelper::generateSalt('FakeHandshakeSalt'.$this->_email); //security through obscurity
                return is_null($this->_user) ? $emailSalt : $this->_user->OneTimeSalt;
            }
            public function getStorageSalt() {
                $emailSalt      = HashHelper::generateSalt('FakeStorageSalt'.$this->_email); //security through obscurity
                return is_null($this->_user) ? $emailSalt : $this->_user->PasswordSalt;
            }

            /**
             * Login performs ALL operational checks, it will not fail early to avoid giving clues
             * to attackers.  The checks are as follows:
             *  - Verify user account & password.
             *  - Then, verify that the IP is not being blocked (more than 5 failures)
             *  - Then, verify user account is not locked (more than 5 failures)
             *
             *
             * CAREFUL: a non-boolean value with the bitwise operators could cause bugs
             *
             * @param $hashedPassword
             * @return Session|null
             */
            public function login( $hashedPassword ) {
                $handshakeSalt  = $this->getHandshakeSalt();
                $invalidSha     = hash('sha256','SOMESTRINGNEVERUSED');
                $storedPass     = !empty($this->_user) ? $this->_user->StoredPassword : $invalidSha;

                $isValidSoFar   = !is_null($this->_user);
                $isValidSoFar   &= HashHelper::areHashesEqual( HashHelper::hashPassword($handshakeSalt,$storedPass), $hashedPassword);
                $isValidSoFar   &= $this->isIpOkay( $isValidSoFar );

                if( $isValidSoFar == 1 ) {
                    $this->updateUserOnLoginSuccess();
                    return $this->createSession();
                }

                return null;
            }

            #region Internal helper methods
            protected function createSession() {
                $session            = new Session();
                $session->UserId    = $this->_user->Id;
                $this->_repository->save($session);
                return $session;
            }
            protected function updateUserOnLoginSuccess() {
                $this->_user->OneTimeSalt       = HashHelper::generateSalt();
                $this->_user->LastLoginDateTime = DateTime::now();
                $this->_repository->save($this->_user);
            }

            /**
             * We will block IPs using an exponential algorithm that will start after 5 failed attempts.
             * The 6th failed attempt will result in a 15 minute wait before the next attempt is allowed,
             * and a 7th failure will bump that to 30 minutes before the next attempt is allowed, so we
             * will effectively be permanently blocking that IP as it continues to fail. Because of this
             * we will need to unblock an IP if it succeeds with a "forgot password" process.
             *
             * CAREFUL: a non-boolean value with the bitwise operators could cause bugs
             *
             * @param bool $isValidCredentials
             * @return bool
             */
            protected function isIpOkay( $isValidCredentials ) {
                /** @var LoginFailure $loginFailure */
                $loginFailure                   = $this->_repository->search( LoginFailureSearch::where()
                                                                  ->IpAddress->is( $_SERVER['REMOTE_ADDR'] ) )
                                                    ->firstOrDefault(new LoginFailure());
                $loginFailure->IpAddress        = $_SERVER['REMOTE_ADDR'];
                $now                            = DateTime::now();
                $loginFailure->FailureCount    += $isValidCredentials ? 0 : 1;
                $notAllowedUntil                = $loginFailure->FailureCount > self::MaxAttemptsBeforeIpBackOff
                                                ? $loginFailure->StartDateTime->addMinutes(
                                                    7.5 * pow(2,$loginFailure->FailureCount - self::MaxAttemptsBeforeIpBackOff)
                                                  )
                                                : $now;
                $allowToPass                    = $isValidCredentials & (
                                                    $loginFailure->FailureCount <= self::MaxAttemptsBeforeIpBackOff
                                                    | $now >= $notAllowedUntil
                                                );

                $loginFailure->FailureCount     = $allowToPass ? 0 : $loginFailure->FailureCount;
                $loginFailure->StartDateTime    = DateTime::now();
                $this->_repository->save($loginFailure);

                return $allowToPass;
            }
            #endregion
        }
    }