<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
    class Injector {
        protected static $_dependencies     = array();
        protected static $_noSingletons     = array();
        protected static $_singletons       = array();

        public static function get( $key ) {
            if( isset(self::$_noSingletons[$key]) ) {
                throw new \Exception("Singleton not allowed for {$key}.  You must use Injector::getNew('{$key}')");
            }
            if( !isset(self::$_singletons[$key]) ) {
                self::$_singletons[$key] = self::getNew($key);
            }
            return self::$_singletons[$key];
        }
        public static function getNew( $key ) {
            if( !self::isDefined($key) ) {
                try {
                    return new $key();
                }
                catch( \Exception $ex ) {
                    throw new \Exception("Undefined Dependency Injection: '{$key}'.");
                }
            }
            return new self::$_dependencies[$key]();
        }
        public static function defineDependency( $key, $namespace, $allowSingleton=true ) {
            self::$_dependencies[$key]      = $namespace;
            if( !$allowSingleton ) {
                self::$_noSingletons[$key]  = true;
            }
        }
        public static function defineDependencies( array $arr ) {
            self::$_dependencies            = array_merge(self::$_dependencies,$arr);
        }
        public static function disallowSingleton( $key ) {
            self::$_noSingletons[$key]      = true;
        }
        protected static function isDefined( $key ) {
            return isset(self::$_dependencies[$key]);
        }
    }
}