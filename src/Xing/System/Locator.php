<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    /**
     * Class Locator
     * @package Xing\System
     */
    class Locator {
        protected static $_services         = array();
        protected static $_noSingletons     = array();
        protected static $_singletons       = array();

        public static function get( $key ) {
            if( isset(self::$_noSingletons[$key]) ) {
                throw new \Exception("Singleton not allowed for {$key}.  You must use Locator::getNew('{$key}')");
            }
            if( !isset(self::$_singletons[$key]) ) {
                self::$_singletons[$key] = self::getNew($key);
            }
            return self::$_singletons[$key];
        }
        public static function getNew( $key ) {
            try {
                if( !self::isDefined($key) ) {
                    return new $key();
                }
                return new self::$_services[$key]();
            }
            catch( \Exception $ex ) {
                throw new \Exception("Undefined Dependency Injection: '{$key}'.");
            }
        }
        public static function defineService( $key, $namespace, $allowSingleton=true ) {
            self::$_services[$key]      = $namespace;
            if( !$allowSingleton ) {
                self::$_noSingletons[$key]  = true;
            }
        }
        public static function defineServices( array $arr ) {
            self::$_services            = array_merge(self::$_services,$arr);
        }
        public static function disallowSingleton( $key ) {
            self::$_noSingletons[$key]      = true;
        }
        public static function isDefined( $key ) {
            return isset(self::$_services[$key]);
        }
    }
}