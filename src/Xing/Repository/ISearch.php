<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
	interface ISearch {
		public function getModelInstance();
        public function getOperations();
	}
}