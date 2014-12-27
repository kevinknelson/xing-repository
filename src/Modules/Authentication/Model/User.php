<?php

    namespace Modules\Authentication\Model {
        use Xing\Models\Entity\AEntity;
        use Xing\System\Collections\Dictionary;
        use Xing\System\DateTime\DateTime;
        use Xing\System\Guid;

        /**
         * Class User
         * @package Modules\Authentication\Model
         *
         * @property string $StoredPassword
         * @property string $OneTimeSalt
         * @property string $PasswordSalt
         */
        class User extends AEntity {
            /** @var string */
            private $_storedPassword;
            /** @var string */
            private $_oneTimeSalt;
            /** @var string */
            private $_passwordSalt;

            /** @var string */
            public $Email;
            /** @var string */
            public $FullName;
            /** @var DateTime */
            public $CreatedDateTime;
            /** @var DateTime */
            public $UpdatedDateTime;
            /** @var DateTime */
            public $LastLoginDateTime;

            /** @var DateTime */
            public $FailedLoginDateTime;
            /** @var int */
            public $FailedLoginCount;
            /** @var bool */
            public $IsFailedLoginNotified;

            /** @var Dictionary */
            public $Roles;

            public function changePassword( $newPassword ) {
                $this->OneTimeSalt            = Guid::create();
                $this->PasswordSalt             = Guid::create();
                $this->StoredPassword           = hash('sha256',$this->PasswordSalt.$newPassword);
                $this->FailedLoginDateTime      = null;
                $this->FailedLoginCount         = 0;
                $this->IsFailedLoginNotified    = false;
            }
            protected function get_StoredPassword() {
                return $this->_storedPassword;
            }
            protected function set_StoredPassword( $storedPassword ) {
                $this->_storedPassword = $storedPassword;
            }
            protected function get_OneTimeSalt() {
                return $this->_oneTimeSalt;
            }
            protected function set_OneTimeSalt( $oneTimeSalt ) {
                $this->_oneTimeSalt = $oneTimeSalt;
            }
            protected function get_PasswordSalt() {
                return $this->_passwordSalt;
            }
            protected function set_PasswordSalt( $passwordSalt ) {
                $this->_passwordSalt = $passwordSalt;
            }
        }
    }