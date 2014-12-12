<?php
    /**
     * Xing-Repository Usage Example Entity
     *
     * @copyright 2013 Kevin K. Nelson (xingcreative.com)
     * Licensed under the MIT license
     */
    namespace Example {
        use Xing\Models\Entity\AEntity;

        class User extends AEntity {
            protected $_id;
            protected $_email;
            protected $_username;
            protected $_storedPassword;

            public function isPasswordValid( $password ) {
                return crypt($password, $this->_storedPassword) == $this->_storedPassword;
            }
            public function setNewPassword( $existing, $new ) {
                if( $this->isPasswordValid($existing) ) {
                    $this->forceNewPassword( $new );
                }
            }
            public function forceNewPassword( $newPassword ) {
                $this->_storedPassword	= crypt($newPassword);
            }
            protected function get_Id() {
                return $this->_id;
            }
            protected function set_Id( $id ) {
                $this->_id = $id;
            }
            protected function get_Email() {
                return $this->_email;
            }
            protected function set_Email( $email ) {
                $this->_email = $email;
            }
            protected function get_StoredPassword() {
                return $this->_storedPassword;
            }
            protected function set_StoredPassword( $storedPassword ) {
                $this->_storedPassword = $storedPassword;
            }
            protected function get_Username() {
                return $this->_username;
            }
            protected function set_Username( $username ) {
                $this->_username = $username;
            }
        }
    }