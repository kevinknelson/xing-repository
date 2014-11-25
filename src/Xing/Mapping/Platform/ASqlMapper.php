<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping {

    use Xing\Mapping\PropertyMap\IPropertyMap;
    use Xing\Mapping\PropertyMap\PropertyMap;
    use Xing\Repository\AEntity;
    use Xing\Repository\AIntelliSearch;
    use Xing\Repository\ISearch;
    use Xing\Repository\SearchOperation;
    use Xing\Repository\SearchOperator;
    use Xing\Repository\Sql\ISqlQuery;
    use Xing\System\Collections\Collection;
    use Xing\System\Locator;

    abstract class ASqlMapper extends ABaseMapper {
        /** @var ISqlQuery */
        protected $_query;
        protected $_pdo;
        protected $_map         = null;

        final private function __construct() {
            $this->_map         = $this->getPropertyMap();
            $this->_query       = Locator::get('Xing\Repository\Sql\MySqlQuery');
            $this->_pdo         = $this->_query->Pdo;
        }
        public function init() {}
        abstract public function getPropertyMap();
        abstract public function getTableName();
        abstract public function getPrimaryKey();

        public function buildQuery( ISearch $searchObject ) {

        }
        /**
         * @param $searchObject
         * @return AEntity[]|Collection
         */
        public function search( ISearch $searchObject ) {
            $this->_query->select('*')->from( $this->getTableName(), 'T' );

            if( $searchObject instanceof AIntelliSearch ) {
                $this->applyOperations($this->_query, $searchObject->getOperations());
                $this->_query->limit( $searchObject->getLimit(), $searchObject->getLimitOffset() );
            }
        }

        /**
         * @param ISearch $searchObject
         * @return bool
         */
        public function exists( ISearch $searchObject ) {
            // TODO: Implement exists() method.
        }

        /**
         * @param ISearch $searchObject
         * @return int
         */
        public function count( ISearch $searchObject ) {
            // TODO: Implement count() method.
        }

        /**
         * @param AEntity $entity
         * @return void
         */
        public function save( AEntity $entity ) {
            // TODO: Implement save() method.
        }

        /**
         * @param AEntity $entity
         * @return void
         */
        public function remove( AEntity $entity ) {
            // TODO: Implement remove() method.
        }

        /**
         * @param ISearch $searchObject
         * @return void
         */
        public function deleteWhere( ISearch $searchObject ) {
            // TODO: Implement deleteWhere() method.
        }
        protected function applyOperations(ISqlQuery $query, $operations, $map=null) {
			$map				= $map ?: $this->getPropertyMap();
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
                    $isInType       = $operation->Operator->isIn(SearchOperator::IsInSet,SearchOperator::IsNotInSet);
                    $column         = is_null($map) ? $operation->Key : $map[$operation->Key];
                    $columnName     = $column instanceof IPropertyMap ? $column->getColumnNameForQuery() : $column;
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
    }
}