<?php
/**
 * @package Xing\Mapping\Sql
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping\Sql {

    /**
     * ISqlQuery is an interface for the dynamic building of queries based on methods
     * that reflect the SQL language. By querying via method calls instead of direct
     * SQL, we are able to build the query for the specific database targeted in the
     * inheriting concrete class without changing the coded query.  E.g. we can
     * do things such as "TOP 200" for MSSQL or "LIMIT 200" for MySQL, but still use
     * the same limit() method from the ISqlQuery interface.
     *
     * The primary "features" of the interface are that it makes it possible to build
     * WHERE statements dynamically.  Using the where, andWhere, etc., if the value
     * passed into the statement is null, it will NOT become part of the SQL query.
     * However, if it has any other value, the parameter will be placed into the {0}
     * string of the where clause.
     *
     * The other primary feature is the ability to auto-map the results of a query to
     * specific objects by utilizing the IMapper interface and mapping a DB array
     * to the object and vice-versa.
     *
     */
    interface ISqlQuery {
        public function columnExists( $table, $column );
        public function tableExists( $table );
        public function indexExists( $table, $index );
        public function execute( $sql );
        public function executeFormat( $sql );
        public function delete();
        /**
         * @param string $columns
         * @return ISqlQuery
         */
        public function select( $columns='*' );
        /**
         * @param $table
         * @param $alias
         * @return ISqlQuery
         */
        public function from( $table, $alias='' );
        /**
         * @param $table
         * @param $condition
         * @param $alias
         * @return ISqlQuery
         */
        public function leftJoin($table, $condition, $alias='');
        /**
         * @param $sql
         * @param $value
         * @return ISqlQuery
         */
        public function where( $sql, $value );
        /**
         * @param string $sql e.g. WHERE IsActive={0}
         * @param array $value
         * @return ISqlQuery
         */
        public function whereIn( $sql, array $value=null );
        /**
         * @param string $sql
         * @param mixed $value
         * @return ISqlQuery
         */
        public function andWhere( $sql, $value );
        /**
         * @param string $sql
         * @param array $value
         * @return ISqlQuery
         */
        public function andWhereIn( $sql, array $value=null );
        /**
         * @param string $sql
         * @param mixed $param1
         * @param mixed $param2
         * @return ISqlQuery
         */
        public function andWhereBetween( $sql, $param1, $param2 );
        /**
         * @param string $sql
         * @param mixed $value
         * @return ISqlQuery
         */
        public function orWhere( $sql, $value );
        /**
         * @param string $sql
         * @param array $value
         * @return ISqlQuery
         */
        public function orWhereIn( $sql, array $value=null );
        /**
         * @param string $sql
         * @param mixed $param1
         * @param mixed $param2
         * @return ISqlQuery
         */
        public function orWhereBetween( $sql, $param1, $param2 );
        /**
         * @return ISqlQuery
         */
        public function groupAnd();
        /**
         * @return ISqlQuery
         */
        public function groupOr();
        /**
         * @param string $columnList
         * @return ISqlQuery
         */
        public function groupBy( $columnList );

        /**
         * @param $condition
         * @return ISqlQuery
         */
        public function having( $condition );

        /**
         * @param string $orderBy
         * @return ISqlQuery
         */
        public function setOrderBy( $orderBy );
        /**
         * @param string $key
         * @param string $direction
         * @return ISqlQuery
         */
        public function orderBy( $key, $direction );
        /**
         * @param int $count
         * @param int $offset
         * @return ISqlQuery
         */
        public function limit( $count, $offset=0 );
        /**
         * @param int $pageNumber
         * @param int $rowsPerPage
         * @return ISqlQuery
         */
        public function paged( $pageNumber, $rowsPerPage=50 );

        public function saveArray( $tableName, $primaryKeyName, $columns );
        /**
         * @param string $tableName
         * @param string $primaryKeyName
         * @param mixed $id
         * @return ISqlQuery
         */
        public function remove( $tableName, $primaryKeyName, $id );

        /**
         * @param null $primaryKey
         * @return int
         */
        public function getCount($primaryKey=null);
        public function exists();
        /**
         * @return array
         */
        public function getArray();
        /**
         * @return bool|\PDOStatement
         */
        public function getPdoResult();
        /**
         * @return string
         */
        public function getQuery();
        /**
         * @param string $columns
         * @return ISqlQuery
         */
        public function addColumns( $columns );
        /**
         * @param array  $array
         * @param string $defaultSort
         * @param string $defaultDirection
         * @return ISqlQuery
         */
        public function setOrderOptions( array $array, $defaultSort, $defaultDirection='ASC' );
    }
}
