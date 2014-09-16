<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Exception {
    use Xing\System\Http\HttpResponseCode;
    use Exception;

    class HttpException extends Exception {
        protected $_httpCode;

        public function __construct(HttpResponseCode $httpCode, $message = "", $code = 0, Exception $previous = null) {
            parent::__construct($message,$code,$previous);
            $this->_httpCode    = $httpCode;
        }
        public function getHttpResponseCode() {
            return $this->_httpCode;
        }
        public function getHttpMessage() {
            return empty($this->message) ? $this->_httpCode->DefaultMessage : $this->message;
        }
    }
}