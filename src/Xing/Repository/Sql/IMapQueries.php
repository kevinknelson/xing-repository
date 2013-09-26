<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository\Sql {
	use Xing\Repository\ISearch;
    use Xing\Repository\Sql\ISqlQuery;

    interface IMapQueries {
        /**
         * @param ISqlQuery $query
         * @param ISearch   $search
         * @return ISqlQuery
         */
        public function buildQuery( ISqlQuery $query, ISearch $search );
	}
}