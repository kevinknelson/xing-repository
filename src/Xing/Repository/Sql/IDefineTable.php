<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository\Sql {
	use Xing\Repository\AEntity;

	interface IDefineTable {
		public function getTableName();
		public function getPrimaryKey();
	}
}