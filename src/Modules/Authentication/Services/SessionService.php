<?php

    namespace Modules\Authentication\Services {
        use Xing\Repository\IRepository;
        use Xing\System\Exception\HttpException;
        use Xing\System\Http\HttpResponseCode;
        use Xing\System\Locator;
        use Modules\Authentication\Helpers\HashHelper;
        use Modules\Authentication\Model\Search\SessionSearch;
        use Modules\Authentication\Model\Search\UserSearch;
        use Modules\Authentication\Model\Session;
        use Modules\Authentication\Model\User;

        class SessionService {
            /** @var IRepository */
            private $_repository;
            /** @var Session|null */
            private $_session;
            /** @var User|null */
            private $_user;

            public function __construct( $sessionId ) {
                $this->_repository      = Locator::get('IRepository');
                $this->_session         = $this->_repository->search(SessionSearch::where()->Id->is($sessionId))->firstOrDefault();
                if( !is_null($this->_session) && $this->_session->isValidSession() ) {
                    $this->_user        = $this->_repository->search(UserSearch::where()->Id->is($this->_session->UserId))->firstOrDefault();
                }
            }
            public function validateUserSession() {
                if( is_null($this->_session) || is_null($this->_user) ) {
                    throw new HttpException( HttpResponseCode::Unauthorized() );
                }
                $sharedSecret           = $this->_user->StoredPassword;
                $method                 = $_SERVER['REQUEST_METHOD'];
                $protocol               = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ? 'http' : 'https';
                $fullPath               = "{$protocol}://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";
                $body                   = trim(file_get_contents('php://input'));
                $clientSignature        = $_SERVER['HTTP_SIGNATURE'];
                $serverSignature        = hash('sha256',$sharedSecret.$method.$fullPath.$body);

                if( !HashHelper::areHashesEqual($clientSignature,$serverSignature) ) {
                    throw new HttpException( HttpResponseCode::Unauthorized() );
                }
            }
        }
    }