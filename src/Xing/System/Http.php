<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    class Http {
        public static function isPostBack() {
            return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
        }
        public static function get($name,$default=NULL) {
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        }
        public static function post($name, $default=NULL) {
            return isset($_POST[$name]) ? $_POST[$name] : $default;
        }
        public static function request($name, $default=NULL) {
            return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
        }
        public static function cookie($name, $default=NULL) {
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
        }
        public static function server($name, $default=NULL) {
            return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
        }
        public static function redirect( $uri ) {
            if( !headers_sent() ) {
                header("Location: {$uri}");
            }
            else {
                echo("<div class='showonlythis'>We are attempting to redirect you.<br />
                    If you do not get redirected immediately, JavaScript may be disabled.<br />
                    Please <a href='{$uri}'>click here</a> to continue.</div>");
                echo("<script type='text/javascript'>window.location='{$uri}'</script>");
            }
            exit;
        }
    }
}
