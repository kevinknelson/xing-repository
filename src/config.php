<?php
/**
 * Xing Configuration File
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */

    namespace {
        use Xing\Mapping\Sql\DbConfig;
        use Xing\System\Exception\ErrorHandler;
        use Xing\System\Http\Http;
        use Xing\System\Locator;

        date_default_timezone_set('UTC');
        spl_autoload_register( function( $class ) {
            require_once(__DIR__.'/'.str_replace('\\','/',$class.'.php'));
        });

        error_reporting(E_ALL);
        set_error_handler(ErrorHandler::getErrorHandler(),E_ALL);

        Http::configure( __DIR__."/../" );

        Locator::defineServices( array(
            'IRepository'           => 'Xing\Mapping\MappingRepository',
            'ISqlQuery'             => 'Xing\Mapping\Sql\MySqlQuery',
            'IRouter'               => 'Xing\Mvc\SimpleJsonRouter',
            'Example\User\Mapper'   => '\Example\Mapper\UserMapper'
        ) );

        DbConfig::instance()
                ->setDriver( DbConfig::Sqlite )
                ->setConfig('data/example.db','user','pass');
    }