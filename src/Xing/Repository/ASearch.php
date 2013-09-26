<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
	use Xing\System\APropertiedObject;

	/**
	 * @property-read SearchOperation[] $Operations
     *
     * @method $this is()
     * @method $this isNot()
     * @method $this isIn()
     * @method $this isNotIn( array $value=null, $ignoreNullValues=true )
     * @method $this isGreaterThan($value, $ignoreNullValues=true)
     * @method $this isGreaterThanOrEqualTo( $value, $ignoreNullValues=true )
     * @method $this isLessThan( $value, $ignoreNullValues=true )
     * @method $this isLessThanOrEqualTo( $value, $ignoreNullValues=true )
	 */
	abstract class ASearch extends APropertiedObject implements ISearch {
		protected $_operations;

		abstract protected function defineProperties();

		final public function __construct() {
			$this->_operations		= array();
			$this->defineProperties();
		}

        /**
         * @param SearchOperator $operator
         * @param null           $key
         * @param null           $value
         * @return $this
         */
        public function addOperation( SearchOperator $operator, $key=null, $value=null ) {
			$this->_operations[]	= new SearchOperation($operator, $key, $value);
			return $this;
		}
		public function get_Operations() {
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
	}
}