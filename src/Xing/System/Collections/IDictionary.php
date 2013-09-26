<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
	interface IDictionary extends IEnumerable {
		public function add( $key, $item );
		public function remove( $key );
		public function containsKey( $key );
		public function getValueOrDefault( $key, $default=null );
		public function tryGetValue( $key, &$value );
	}
}