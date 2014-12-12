<?php
/**
 * @package Xing\Models\Search
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Models\Search {
	interface ISearch {
		public function getModelInstance();
        public function getOperations();
	}
}