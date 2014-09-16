<?php

    namespace Xing\System\Exception {
        class SqlException extends \Exception {
            protected $_sql;

            public function __construct( $error, $sql, \Exception $previous = null ) {
                parent::__construct("SQL Exception: '{$error}'", 0);
                $this->_sql = $sql;
            }
            public function getFailedSql() {
                return $this->_sql;
            }
        }
    }
