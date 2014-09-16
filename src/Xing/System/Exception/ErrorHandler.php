<?php

    namespace Xing\System\Exception {
        class ErrorHandler {
            public static function getErrorHandler() {
                return function($errno, $errstr, $errfile, $errline) {
                    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
                };
            }
            public static function logException( \Exception $ex ) {
                $sql        = $ex instanceof SqlException ? "; SQL: ".$ex->getFailedSql() : '';
                error_log('URI: '.$_SERVER['REQUEST_URI'].'; MSG: '.$ex->getMessage().'; FILE: '.$ex->getFile().'; LINE: '.$ex->getLine().$sql);
            }
        }
    }