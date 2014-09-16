<?php

    namespace Xing\System\Http {
        use Xing\System\APropertiedObject;
        use Xing\System\Collections\DictionaryCast;

        /**
         * Class Uri
         * @package Xing\System
         *
         * @property-read string $Path
         * @property-read string $UriParts
         * @property-read DictionaryCast $PostData
         */
        class Uri extends APropertiedObject {
            private $_path;
            private $_params;
            private $_postData;

            public function __construct( $uri ) {
                $uriParts           = explode('?',str_replace( Http::basePath(), '', $_SERVER['REQUEST_URI'] ));
                $queryString        = isset($uriParts[1]) ? $uriParts[1] : '';
                $this->_path        = $uriParts[0];
                $this->_params      = array();
                $this->_postData    = array();
                parse_str($queryString, $this->_params);
                $this->setPostData();
            }
            public function get( $paramName, $default=null ) {
                return isset($this->_params[$paramName]) ? $this->_params[$paramName] : $default;
            }
            protected function get_Path() {
                return $this->_path;
            }
            protected function get_UriParts() {
                return explode('/',ltrim($this->_path,'/'));
            }
            protected function get_PostData() {
                return $this->_postData;
            }
            private function setPostData() {
                $postData = array();
                if( $_SERVER['REQUEST_METHOD']=='POST' || $_SERVER['REQUEST_METHOD']=='PUT' ) {
                    if( strpos($_SERVER['CONTENT_TYPE'],'json') !== false ) {
                        $postData = json_decode(file_get_contents("php://input"),true);
                    }
                    else {
                        parse_str(file_get_contents("php://input"),$postData);
                    }
                }
                $this->_postData    = empty($postData) ? new DictionaryCast() : new DictionaryCast($postData);
            }
        }
    }