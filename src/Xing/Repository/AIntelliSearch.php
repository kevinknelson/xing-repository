<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
    use Xing\System\Collections\KeyList;
    use Xing\System\Exception\UndefinedPropertyException;

    abstract class AIntelliSearch implements ISearch {
		protected $_operations;
        protected $_properties;
        protected $_limit;
        protected $_limitOffset;
        protected $_key;

		protected function defineProperties() {
            $this->_properties->addRange( array('Id') );
        }

		final public function __construct() {
			$this->_operations		= array();
            $this->_properties      = new KeyList();
			$this->defineProperties();
		}
        public function __get( $varName ) {
            if( !$this->_properties->has($varName) ) {
                throw new UndefinedPropertyException($varName);
            }
            $this->_key = $varName;
            return $this;
        }
        public function __set( $varName, $value ) {
            throw new \Exception('Write properties not allowed for AIntelliSearch');
        }
        public function getLimit() {
            return $this->_limit;
        }
        public function getLimitOffset() {
            return $this->_limitOffset;
        }

        #region QUERY-BUILDING/CHAIN-ABLE METHODS
        public function page( $itemsPerPage, $pageNumber ) {
            $this->_limitOffset = (($pageNumber ?: 1)-1) * $itemsPerPage;
            $this->_limit       = $itemsPerPage;
            return $this;
        }
        public function limit( $count, $offset=0 ) {
            $this->_limit       = $count;
            $this->_limitOffset = $offset;
            return $this;
        }
		public function is( $value, $ignoreNullValues=false ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsEqualTo(), $this->_key, $value );
			}
			return $this;
		}
		public function isNot( $value, $ignoreNullValues=false ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsNotEqualTo(), $this->_key, $value );
			}
			return $this;
		}
		public function isIn( array $value=null, $ignoreNullValues=true ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsInSet(), $this->_key, $value );
			}
			return $this;
		}
		public function isNotIn( array $value=null, $ignoreNullValues=true ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsNotInSet(), $this->_key, $value );
			}
			return $this;
		}
		public function isGreaterThan( $value, $ignoreNullValues=true ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsGreaterThan(), $this->_key, $value );
			}
			return $this;
		}
		public function isGreaterThanOrEqualTo( $value, $ignoreNullValues=true ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsGreaterThanOrEqualTo(), $this->_key, $value );
			}
			return $this;
		}
		public function isLessThan( $value, $ignoreNullValues=true ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsLessThan(), $this->_key, $value );
			}
			return $this;
		}
		public function isLessThanOrEqualTo( $value, $ignoreNullValues=true ) {
			if( !is_null($value) || !$ignoreNullValues ) {
				$this->addOperation( SearchOperator::IsLessThanOrEqualTo(), $this->_key, $value );
			}
			return $this;
        }
        public function addOperation( SearchOperator $operator, $key=null, $value=null ) {
            if( is_null($this->_key) ) {
                throw new \Exception('You must select property to use before calling a search operation');
            }
			$this->_operations[]	= new SearchOperation($operator, $key, $value);
            $this->_key             = null;
			return $this;
		}
		public function getOperations() {
			return $this->_operations;
		}
		public function andThe() {
			$this->_operations[]	= new SearchOperation(SearchOperator::AndNext());
			return $this;
		}
		public function orThe() {
			$this->_operations[]	= new SearchOperation(SearchOperator::OrNext());
			return $this;
		}
		public function allPreviousAnd() {
			$this->_operations[]	= new SearchOperation(SearchOperator::GroupPreviousAndNext());
			return $this;
		}
		public function allPreviousOr() {
			$this->_operations[]	= new SearchOperation(SearchOperator::GroupPreviousOrNext());
			return $this;
		}
		#endregion
    }
}