<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository\Sql {
    use PDO;
    use Xing\Repository\AEntity;
    use Xing\Repository\DbConfig;
    use Xing\Repository\EntityCollection;
    use Xing\System\Collections\Xinq;
    use Xing\System\APropertiedObject;
    use Xing\System\Format;

    /**
     * @property-read PDO $Pdo;
     */
    class MySqlQuery extends APropertiedObject implements ISqlQuery {
        const AND_TYPE = 1;
        const OR_TYPE  = 2;

        private $_columnList;
        private $_from;
        private $_fromAlias;
        private $_leftJoins;
        private $_currentWhereList;
        private $_currentListType;
        private $_where;
        private $_orderOptions;
        private $_orderBy;
        private $_groupBy;
        private $_having;
        private $_limit;
        private $_pdo;

        #region GETTERS
        protected function get_Pdo() {
            if( is_null($this->_pdo) ) {
                $this->_pdo = DbConfig::instance()->getPdoConnection();
                $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->_pdo;
        }
        #endregion

        public function __construct() {
            $this->_where               = '';
            $this->_groupBy             = '';
            $this->_having				= '';
            $this->_orderBy             = '';
            $this->_limit               = '';
            $this->_columnList          = '*';
            $this->_leftJoins           = array();
            $this->_currentWhereList    = array();
            $this->_currentListType     = self::AND_TYPE;
        }
        private function definitionExists( $sql ) {
            $result	= $this->Pdo->query($sql);
            return $result===false ? false : $result->rowCount() > 0;
        }
        public function columnExists( $table, $column ) {
            $column	= $this->Pdo->quote($column);
            $sql 	= "SHOW COLUMNS FROM {$table} LIKE {$column}";
            $return = $this->definitionExists($sql);
            return $return;
        }
        public function indexExists( $table, $index ) {
            $index	= $this->Pdo->quote($index);
            $sql 	= "SHOW INDEX FROM {$table} WHERE Key_name LIKE {$index}";
            return $this->definitionExists($sql);
        }
        public function tableExists( $table ) {
            $table	= $this->Pdo->quote($table);
            $sql 	= "SHOW TABLES LIKE {$table}";
            return $this->definitionExists($sql);
        }

        #region CHAIN-ABLE METHODS
        public function setOrderOptions( array $array, $defaultSort, $defaultDirection='ASC' ) {
            $this->_orderOptions    = $array;
            $this->orderBy($defaultSort, $defaultDirection);
            return $this;
        }
        public function select( $columns='*' ) {
            $this->_columnList = $columns;
            return $this;
        }
        public function addColumns( $columns ) {
            $this->_columnList .= ', '.$columns;
            return $this;
        }
        public function from( $table, $alias='' ) {
            $this->_from    	= $table;
            $this->_fromAlias	= $alias;
            return $this;
        }
        public function leftJoin($table, $condition, $alias='') {
            $this->_leftJoins[] = "LEFT JOIN {$table} {$alias} ON {$condition}";
            return $this;
        }
        public function where( $sql, $value ) {
            $this->_currentWhereList[]   = $this->getJoiner("AND").str_replace("{0}",is_null($value) ? 'NULL' : $this->Pdo->quote((string)$value),$sql);
            return $this;
        }
        public function whereIn( $sql, array $value=null ) {
            $this->_currentWhereList[] = $this->getJoiner("AND") . str_replace("{0}",is_null($value) ? 'NULL' : $this->escapeImplode($value), $sql);
            return $this;
        }
        public function andWhere( $sql, $value ) {
            $this->_currentWhereList[]   = $this->getJoiner("AND").str_replace("{0}",is_null($value) ? 'NULL' : $this->Pdo->quote((string)$value),$sql);
            return $this;
        }
        public function andWhereIn( $sql, array $value=null ) {
            $this->_currentWhereList[] = $this->getJoiner("AND") . str_replace("{0}",is_null($value) ? 'NULL' : $this->escapeImplode($value), $sql);
            return $this;
        }
        public function orWhereIn( $sql, array $value=null ) {
            $this->_currentWhereList[] = $this->getJoiner("OR") . str_replace("{0}",is_null($value) ? 'NULL' : $this->escapeImplode($value), $sql);
            return $this;
        }
        public function orWhere( $sql, $value ) {
            $this->_currentWhereList[]   = $this->getJoiner("OR").str_replace("{0}",is_null($value) ? 'NULL' : $this->Pdo->quote((string)$value),$sql);
            return $this;
        }
        public function groupAnd() {
            $this->buildWhere();
            $this->_currentWhereList    = array();
            $this->_currentListType     = self::AND_TYPE;
            return $this;
        }
        public function groupOr() {
            $this->buildWhere();
            $this->_currentWhereList    = array();
            $this->_currentListType     = self::OR_TYPE;
            return $this;
        }
        public function setOrderBy( $orderBy ) {
            $this->_orderBy = "ORDER BY {$orderBy}";
            return $this;
        }
        public function orderBy( $key, $direction ) {
            if( !is_null($key) ) {
                if( count($this->_orderOptions) == 0 ) {
                    $this->_orderBy = "ORDER BY {$key} {$direction}";
                }
                elseif( !empty($this->_orderOptions[$key]) ) {
                    $reverseDir     = strtoupper($direction)=='ASC' ? 'DESC' : 'ASC';
                    $this->_orderBy = "ORDER BY ".str_replace("{RDIR}",$reverseDir,str_replace("{DIR}",$direction,$this->_orderOptions[$key]));
                }
            }
            return $this;
        }
        public function groupBy( $columnList ) {
            $this->_groupBy = "GROUP BY {$columnList}";
            return $this;
        }
        public function having( $condition ) {
            $this->_having	= "HAVING {$condition}";
            return $this;
        }
        public function limit( $count, $offset=0 ) {
            if( !is_null($count) ) {
                $this->_limit   = 'LIMIT '.intval($offset).', '.intval($count);
            }
            return $this;
        }
        public function paged( $pageNumber, $rowsPerPage=50 ) {
            if( !is_null($pageNumber) ) {
                $this->_limit   = 'LIMIT '.(($pageNumber-1)*$rowsPerPage).', '.$rowsPerPage;
            }
            return $this;
        }
        #endregion

        public function getCollection( AEntity $obj, IMapToEntities $mapper ) {
            $collection = EntityCollection::create(array());
            $results = $this->getPdoResult();
            if( $results !== false ) {
                foreach( $results AS $row ) {
                    $instance	= $mapper->loadEntity(clone $obj,$row);
                    if( $instance instanceof AEntity ) {
                        $instance->setDbLoadingComplete();
                    }
                    $collection->add($instance);
                }
            }
            return $collection;
        }
        public function getCount( $primaryKey=null ) {
            $this->buildWhere();
            $count	= is_null($primaryKey) ? 'COUNT(*)' : "COUNT(DISTINCT {$this->_fromAlias}.{$primaryKey})";
            $sql    = "SELECT {$count} FROM {$this->_from} {$this->_fromAlias}\r\n";
            $sql   .= count($this->_leftJoins)==0   ? '' : implode("\r\n ",$this->_leftJoins)."\r\n";
            $sql   .= empty($this->_where)          ? '' : "\r\nWHERE {$this->_where}\r\n";

            return (int) $this->Pdo->query($sql)->fetchColumn();
        }
        public function getArray() {
            $collection = array();
            $results = $this->getPdoResult();
            if( $results !== false ) {
                foreach( $results AS $row ) {
                    $collection[] = $row;
                }
            }
            return $collection;
        }

        public function getPdoResult() {
            $sql        = $this->getQuery();
            return $this->Pdo->query($sql, PDO::FETCH_ASSOC);
        }
        public function save( AEntity $obj, IMapSaveArrays $mapper ) {
            $saveArrays	= $mapper->getSaveArrays($obj);

            foreach( $saveArrays AS $saveArray ) {
                $this->saveArray( $saveArray->TableName, $saveArray->PrimaryKeyName, $saveArray->Columns );
                if( is_callable($saveArray->setPrimaryKey) ) {
                    call_user_func($saveArray->setPrimaryKey,$this->Pdo->lastInsertId());
                }
            }
        }
        protected function saveArray( $table, $primaryKey, $arr ) {
            $command    = 'INSERT INTO';
            $where      = '';
            $cols       = array();
            foreach( $arr AS $key => $value ) {
                if( $key == $primaryKey && !empty($value) ) {
                    $command    = 'UPDATE ';
                    $where      = " WHERE {$key}=".$this->Pdo->quote((string)$value)."";
                }
                elseif( $value instanceof PreParsedField ) { //e.g. we can't escape and quote "UNHEX(value)"
                    $cols[]		= " {$key}={$value->Value}";
                }
                elseif( $key != $primaryKey ) {
                    $cols[]   = is_null($value) ? " {$key}=NULL" : " {$key}=".$this->Pdo->quote((string)$value);
                }
            }
            $sql        = "{$command} {$table} SET ".implode(',',$cols)." {$where}";

            if( false === $this->Pdo->exec($sql) ) {
                $info   = $this->Pdo->errorInfo();
                throw new SqlException($info[2],$sql);
            }
        }
        public function remove( $table, $primaryKey, $id ) {
            $this->Pdo
                ->prepare("DELETE FROM {$table} WHERE {$primaryKey}=".$this->Pdo->quote((string)$id))
                ->execute();
            return $this;
        }
        /**
         * @param string $sql
         * @return int
         */
        public function execute( $sql ) {
            return $this->Pdo->exec($sql);
        }

        public function executeFormat( $sql ) {
            $args   = func_get_args();
            $string = array_shift($args);
            for( $i=0; $i < count($args); $i++ ) {
                $string = str_replace('{'.$i.'}',$this->Pdo->quote($args[$i]),$string);
            }
            return $this->execute($string);
        }
        public function delete() {
            $this->buildWhere();
            if( empty($this->_where) ) {
                throw new \Exception("You cannot delete without specifying conditions");
            }
            return $this->execute("DELETE FROM {$this->_fromAlias} USING {$this->_from} {$this->_fromAlias} WHERE {$this->_where}");
        }
        /**
         * Gets Final Sql Where or empty string if no conditions were loaded.
         * @return string
         */
        public function getQuery() {
            $this->buildWhere();
            $str    = "SELECT {$this->_columnList} FROM {$this->_from} {$this->_fromAlias}\r\n";
            $str   .= count($this->_leftJoins)==0   ? '' : implode("\r\n ",$this->_leftJoins)."\r\n";
            $str   .= empty($this->_where)          ? '' : "\r\nWHERE {$this->_where}\r\n";
            $str   .= "{$this->_groupBy}\r\n";
            $str   .= "{$this->_having}\r\n";
            $str   .= "{$this->_orderBy}\r\n";
            $str   .= "{$this->_limit}";
            return $str;
        }
        /**
         * @param string $andOr
         * @return string
         */
        private function getJoiner( $andOr ) {
            return empty($this->_currentWhereList) ? "" : " {$andOr} ";
        }
        /**
         * Adds "current query" to final _sqlString
         * @return void
         */
        private function buildWhere() {
            if( !empty($this->_currentWhereList) ) {
                if( !empty($this->_where) ) {
                    $this->_where .= ($this->_currentListType==self::AND_TYPE ? " AND " : " OR ");
                }
                $this->_where .= " (".implode(" ",$this->_currentWhereList).") ";
            }
        }
        private function escapeImplode( array $array ) {
            $pdo    = $this->Pdo;
            return Xinq::join($array,',',function($val) use($pdo) {
                return $pdo->quote($val);
            });
        }
    }
}

