<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping\Sql {
    use Xing\System\APropertiedObject;
    use Xing\System\Collections\ValidationErrors;

    /**
     * @property string $Driver
     * @property-read string $Username
     * @property-read string $Password
     * @property-read int $Port
     * @property-read string $Server
     */
    class DbConfig extends APropertiedObject {
        private static $_singleton;
        private $_pdo;
        const MySql     = 'mysql';
        const Sqlite    = 'sqlite';

        private $_driver;
        private $_server;
        private $_database;

        private $_port;
        private $_username;
        private $_password;
        private $_options;

        public static function instance() {
            if( is_null(self::$_singleton) ) { self::$_singleton = new self(); }
            return self::$_singleton;
        }
        private function __construct() {
            $this->_driver  = self::MySql;
            $this->_options = array();
        }
        /**
         * @return \PDO
         */
        public function getPdoConnection() {
            $this->verifyConfig();
            if( is_null($this->_pdo) ) {
                $dsn        = $this->getDsn();
                $this->_pdo = new \PDO($dsn,$this->_username,$this->_password);
            }
            return $this->_pdo;
        }
        public function setConfig( $database, $user, $password, $server='localhost', $port=null ) {
            $this->_server      = $server;
            $this->_database    = $database;
            $this->_username    = $user;
            $this->_password    = $password;
            $this->_port        = $port;
            return $this;
        }
        public function setDriver( $driver ) {
            $this->_driver      = $driver;
            return $this;
        }
        private function verifyConfig() {
            $validationErrors  = new ValidationErrors();
            $validationErrors
                ->addIfEmpty($this->_server,'Server','Database server not defined.')
                ->addIfEmpty($this->_database,'Database','Database name not defined.')
                ->addIfEmpty($this->_username,'Username','Database username not defined.')
                ->addIfEmpty($this->_password,'Password','Database password not defined.');
            if( $validationErrors->hasErrors() ) {
                $arr = $validationErrors->asArray();
                $str = implode('</li><li>',$arr);
                throw new \Exception("Attempt made to connect to the database without all the required information:<ul><li>{$str}</li></ul>");
            }
        }
        private function getDsn() {
            $port       = empty($this->_port) ? '' : ";port={$this->_port}";
            switch( $this->_driver ) {
                case self::Sqlite: return "{$this->_driver}:{$this->_database}";
                case self::MySql: return "{$this->_driver}:dbname={$this->_database};host={$this->_server}{$port}";
            }
        }

        #region GETTERS/SETTERS
        protected function set_Driver( $driver ) {
            $this->_driver = $driver;
        }
        protected function get_Driver() {
            return $this->_driver;
        }
        protected function get_Port() {
            return $this->_port;
        }
        protected function get_Server() {
            return $this->_server;
        }
        protected function get_Username() {
            return $this->_username;
        }
        protected function get_Password() {
            return $this->_password;
        }
        protected function get_Database() {
            return $this->_database;
        }
        #endregion
    }
}
