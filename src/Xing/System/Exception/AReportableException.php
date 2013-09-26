<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Exception {
    abstract class AReportableException extends \Exception {
        protected $_details;
        protected $_previousException;

        public function __construct( $error, $details, \Exception $previous = null ) {
            parent::__construct($error,0,$previous);
            $this->_details             = $details;
        }
        public function getDetails() {
            return $this->_details;
        }
    }
}