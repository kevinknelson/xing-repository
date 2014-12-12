<?php
/**
 * @package Xing\Mapping\Platform
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping\Platform {
    use Xing\Mapping\IRepository;
    use Xing\Mapping\PropertyMap\APropertyMap;
    use Xing\Mapping\Sql\ISqlQuery;
    use Xing\Models\Entity\AEntity;
    use Xing\Models\Search\AIntelliSearch;
    use Xing\Models\Entity\EntityCollection;
    use Xing\Models\Search\ISearch;
    use Xing\Models\Search\SearchOperation;
    use Xing\Models\Search\SearchOperator;
    use Xing\System\Collections\Collection;
    use Xing\System\Locator;

    /**
     * Class ASqlMapper
     * @package Xing\Mapping\Platform
     *
     * @property-read ISqlQuery $SqlQuery
     */
    abstract class ASqlMapper implements IRepository {
        /** @var APropertyMap[] $_map */
        protected $_map         = null;

        final public function __construct() {
            $this->_map         = $this->getPropertyMap();
        }
        public function init() {}

        /**
         * @return APropertyMap[]
         */
        abstract public function getPropertyMap();
        /**
         * @return string
         */
        abstract public function getTableName();
        /**
         * @return string
         */
        abstract public function getPrimaryKey();
        /**
         * @return string
         */
        abstract public function getTableAlias();

        public function buildQuery( ISearch $search ) {
            $query      = $this->getNewSqlQuery()->select( $this->getColumnList() )->from( $this->getTableName(), $this->getTableAlias() );

            if( $search instanceof AIntelliSearch ) {
                $this->applyOperations($query, $search->getOperations());
                $query->limit( $search->getLimit(), $search->getLimitOffset() );
            }

            return $query;
        }
        /**
         * @param $searchObject
         * @return AEntity[]|Collection
         */
        public function search( ISearch $searchObject ) {
            $query          = $this->buildQuery($searchObject);
            $collection     = EntityCollection::create(array());
            $results        = $query->getPdoResult();
            $model          = $searchObject->getModelInstance();

            if( $results !== false ) {
                foreach( $results AS $row ) {
                    $instance	= $this->loadEntity(clone $model, $row);
                    if( $instance instanceof AEntity ) {
                        $instance->setDbLoadingComplete();
                    }
                    $collection->add($instance);
                }
            }
            return $collection;
        }
        public function loadEntity( AEntity $entity, array $arr ) {
            foreach( $this->_map AS $property => $map ) {
                if( $map->isReadable() ) {
                    $entity->{$property}     = $map->fromDbValue($arr);
                }
            }
            return $entity;
        }

        /**
         * @param ISearch $searchObject
         * @return bool
         */
        public function exists( ISearch $searchObject ) {
            $query  = $this->buildQuery($searchObject);
            return $query->getCount() > 0;
        }

        /**
         * @param ISearch $searchObject
         * @return int
         */
        public function count( ISearch $searchObject ) {
            $query  = $this->buildQuery($searchObject);
            return $query->getCount();
        }

        /**
         * @param AEntity $entity
         * @return void
         */
        public function save( AEntity $entity ) {
            $columns        = array();
            foreach( $this->_map AS $property => $map ) {
                if( $map->isWritable() ) {
                    $columns[$map->getColumnName()]  = $map->toDbValue($entity->{$property});
                }
            }
            $this->getNewSqlQuery()->saveArray($this->getTableName(), $this->getPrimaryKey(), $columns);
        }

        /**
         * @param AEntity $entity
         * @return void
         */
        public function remove( AEntity $entity ) {
            $this->getNewSqlQuery()->from( $this->getTableName(), $this->getTableAlias() )->where($this->getPrimaryKey().'={0}',$entity->Id)->delete();
        }

        /**
         * @param ISearch $searchObject
         * @return void
         */
        public function deleteWhere( ISearch $searchObject ) {
            $query  = $this->buildQuery($searchObject);
            $query->delete();
        }
        protected function applyOperations(ISqlQuery $query, $operations, $map=null) {
			$map				= $map ?: $this->_map;
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
                    $column         = is_null($map) ? $operation->Key : $map[$operation->Key];
                    $columnName     = $column instanceof APropertyMap ? $column->getColumnNameForQuery() : $column;

                    if( $operation->Operator->isIn(SearchOperator::IsBetween) ) {
                        if( !is_array($operation->Value) || count($operation->Value) != 2 ) {
                            throw new \InvalidArgumentException("Between Search requires an array parameter with exactly two values");
                        }
                        list( $param1, $param2 )    = $operation->Value;
                        if( is_null($param1) || is_null($param2) ) {
                            throw new \InvalidArgumentException("Neither argument in a Between Search can be NULL");
                        }
                        $method                     = $currentLogical->Value == SearchOperator::AndNext ? 'andWhereBetween' : 'orWhereBetween';
                        call_user_func( array($query,$method), "{$columnName} BETWEEN {0} AND {1}", $param1, $param2);

                    }
                    else {
                        $isInType       = $operation->Operator->isIn(SearchOperator::IsInSet,SearchOperator::IsNotInSet);
                        $sqlOperator    = $this->getSqlOperator($operation->Operator,$operation->Value);
                        $sql            = is_null($operation->Value) ? "{$columnName} IS NULL" : (
                                            $isInType
                                            ? "{$columnName} {$sqlOperator} ({0})"
                                            : "{$columnName} {$sqlOperator} {0}"
                                        );
                        $method         = $currentLogical->Value == SearchOperator::AndNext
                                        ? ($isInType ? 'andWhereIn' : 'andWhere')
                                        : ($isInType ? 'orWhereIn'  : 'orWhere');
                        call_user_func( array($query,$method),$sql, is_null($operation->Value) ? true : $operation->Value );
                    }
                }
            }
        }
        protected function getSqlOperator( SearchOperator $searchOperator, $value ) {
            switch( $searchOperator->Value ) {
                case SearchOperator::IsNotEqualTo:           return is_null($value) ? 'IS NOT' : '<>';
                case SearchOperator::IsInSet:                return 'IN';
                case SearchOperator::IsNotInSet:             return 'NOT IN';
                case SearchOperator::IsGreaterThan:          return '>';
                case SearchOperator::IsGreaterThanOrEqualTo: return '>=';
                case SearchOperator::IsLessThan:             return '<';
                case SearchOperator::IsLessThanOrEqualTo:    return '<=';
                case SearchOperator::IsBetween:              return 'BETWEEN';
                case SearchOperator::IsEqualTo:
                default:                                     return is_null($value) ? 'IS' : '=';
            }
        }
        /**
         * @return ISqlQuery
         * @throws \Exception
         */
        protected function getNewSqlQuery() {
            return Locator::getNew('ISqlQuery');
        }
        /**
         * @return string
         */
        protected function getColumnList() {
            $list   = array();
            foreach( $this->_map AS $map ) {
                if( $map->isReadable() ) {
                    $list[] = $map->getColumnNameForQuery();
                }
            }
            return implode(',',$list);
        }
    }
}