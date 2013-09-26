<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
	/**
	 * Class IEnumerable
	 *
	 * @property int $Count
	 * @property bool $IsEmpty
	 */
	interface IEnumerable extends \IteratorAggregate, \Countable {
		#region GETTERS/SETTERS
		public function get_Count();
		public function get_IsEmpty();
		#endregion

		/**
		 * @return array
		 */
		public function asArray();
		/**
		 * @return self
		 */
		public function clear();

		/**
		 * @param null $default
		 * @return mixed|null
		 */
		public function firstOrDefault( $default=null );

		#region LINQ METHODS
		/**
		 * @param $predicate
		 * @return bool
		 */
		public function any( $predicate );
		/**
		 * @param $predicate
		 * @return bool
		 */
		public function all( $predicate );
		/**
		 * @param $callback
		 * @return IEnumerable|array
		 */
		public function select( $callback );
		/**
		 * @param $predicate
		 * @return IEnumerable|array
		 */
		public function where( $predicate );
		/**
		 * @param $callback
		 * @return IEnumerable|array
		 */
		public function orderByAsc( $callback );
		/**
		 * @param $callback
		 * @return IEnumerable|array
		 */
		public function orderByDesc( $callback );
		/**
		 * @param $callback
		 * @return IEnumerable|array
		 */
		public function forEachItem( $callback );
		#endregion
	}
}