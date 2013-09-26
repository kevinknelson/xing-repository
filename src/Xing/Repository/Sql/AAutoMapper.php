<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository\Sql {
	use Xing\Repository\AEntity;
	use Xing\Repository\ASearch;
    use Xing\Repository\ISearch;
    use Xing\Repository\SearchOperation;
	use Xing\Repository\SearchOperator;
    use Xing\Repository\Sql\ISqlQuery;
    use Xing\System\Collections\Xinq;
    use Xing\System\Format;

    abstract class AAutoMapper implements IMapQueries, IMapToEntities, IMapSaveArrays, IDefineTable {
		protected $_injectedMap;
		protected $_definedMap;
        protected $_tableAlias;
        protected $_columnPrefix;
		/**
		 * If property names of object equal the column names, no mapping is needed
		 * unless you plan to do greedy loading on joins in other objects to avoid conflicts.
		 * if generating a mapping between names, return an array in the format:
		 * array( "propertyName" => "columnName", "propertyName2" => "columnName2" );
		 * @return array|null
		 */
		protected function defineMap() {}

		final public function __construct( $tableAlias=null, $columnPrefix=null ) {
            $this->_tableAlias      = $tableAlias;
            $this->_columnPrefix    = $columnPrefix;
			$this->defineMap();
		}
		final protected function getColumnMap() {
			return $this->_injectedMap ?: $this->_definedMap;
		}
        public function getColumnSelect() {
            $map        = $this->getColumnMap();
            $alias      = empty($this->_tableAlias)     ? '' : $this->_tableAlias.'.';
            $colPrefix  = empty($this->_columnPrefix)   ? '' : $this->_columnPrefix;
            return is_null($map) ? '*' : Xinq::join($map,',',function($val) use( $alias, $colPrefix ) {
                return "{$alias}{$colPrefix}{$val}";
            });
        }
        public function injectMap( $map ) {
			$this->_injectedMap = $map;
        }
		public function buildQuery( ISqlQuery $query, ISearch $search ) {
            $query->select( $this->getColumnSelect() )->from($this->getTableName());
            $this->applyOperations($query,$search->getOperations());
            echo $query->getQuery();
            return $query;
		}
        protected function applyOperations(ISqlQuery $query, $operations) {
			$map				= $this->getColumnMap();
            $currentLogical     = SearchOperator::AndNext();
            foreach( $operations AS $operation ) {
                /** @var $operation SearchOperation */
				if( $operation->Operator->isIn(SearchOperator::AndNext(), SearchOperator::OrNext())) {
					$currentLogical	= $operation->Operator;
				}
				elseif( $operation->Operator->is(SearchOperator::GroupPreviousAndNext()) ) {
					$query->groupAnd();
					$currentLogical	= SearchOperator::AndNext();
				}
				elseif( $operation->Operator->is(SearchOperator::GroupPreviousOrNext()) ) {
					$query->groupOr();
					$currentLogical	= SearchOperator::OrNext();
				}
				else {
                    $isIn           = $operation->Operator->isIn(SearchOperator::IsInSet,SearchOperator::IsNotInSet);
                    $columnName	    = is_null($map) ? $operation->Key : $map[$operation->Key];
                    $sqlOperator    = $this->getSqlOperator($operation->Operator,$operation->Value);
                    $sql            = $isIn
                                    ? "{$columnName} {$sqlOperator} ({0})"
                                    : "{$columnName} {$sqlOperator} {0}";
                    $method         = $currentLogical->Value == SearchOperator::AndNext
                                    ? ($isIn ? 'andWhereIn' : 'andWhere')
                                    : ($isIn ? 'orWhereIn'  : 'orWhere');
                    call_user_func( array($query,$method), $sql, $operation->Value );
                }
            }
        }
        protected function getSqlOperator( SearchOperator $searchOperator, $value ) {
            switch( $searchOperator->Value ) {
                case SearchOperator::IsEqualTo:     return is_null($value) ? 'IS' : '=';
                case SearchOperator::IsNotEqualTo:  return is_null($value) ? 'IS NOT' : '<>';
                case SearchOperator::IsInSet:       return 'IN';
                case SearchOperator::IsNotInSet:    return 'NOT IN';
                case SearchOperator::IsGreaterThan: return '>';
                case SearchOperator::IsGreaterThanOrEqualTo: return '>=';
                case SearchOperator::IsLessThan:    return '<';
                case SearchOperator::IsLessThanOrEqualTo: return '<=';
            }
        }
		public function getSaveArrays( AEntity $entity ) {
			$saveArray	= new SaveArray();
			$saveArray->TableName	= $this->getTableName();
			$saveArray->PrimaryKey	= $this->getPrimaryKey();

			$map		= $this->getColumnMap();
			if( is_null($map) ) {
				$saveArray->Columns		= $entity->asSerializable();
			}
			else {
				foreach( $map AS $property => $column ) {
					$saveArray->Columns[$column]	= $entity->{$property};
				}
			}
			return array( $saveArray );
		}

		public function loadEntity( AEntity $entity, array $arr ) {
			$map = $this->getColumnMap();
			if( is_null($map) ) {
				foreach( $arr AS $key => $value ) {
					$entity->{$key} = $value;
				}
			}
			else {
				foreach( $map AS $property => $column ) {
                    $prefixed   = is_null($this->_columnPrefix) ? $column : $this->_columnPrefix.$column;
					$entity->{$property} = $arr[$prefixed];
				}
			}
			return $entity;
		}

	}
}