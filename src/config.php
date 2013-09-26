<?php
/**
 * Xing Configuration File
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */

use Xing\Repository\DbConfig;
use Xing\Repository\Injector;

date_default_timezone_set('America/Chicago');
spl_autoload_register( function( $class ) {
    require_once(__DIR__.'/'.str_replace('\\','/',$class.'.php'));
});
Injector::defineDependencies( array(
    'ISqlQuery'             => '\Xing\Repository\Sql\SqliteQuery',
    'IRepository'           => '\Xing\Repository\Sql\SqlRepository',
    'Example\User\Mapper'   => '\Example\Mapper\UserMapper'
));
Injector::disallowSingleton('ISqlQuery');

DbConfig::instance()
        ->setDriver( DbConfig::Sqlite )
        ->setConfig('data/example.db','user','pass');