<?php
/**
 * @package Xing\Mapping
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping {
    use Xing\Models\Entity\AEntity;
    use Xing\Models\Search\ISearch;
    use Xing\System\Collections\Collection;

    interface IRepository {
        /**
         * @abstract
         * @param $searchObject
         * @return AEntity[]|Collection
         */
        public function search( ISearch $searchObject );

        /**
         * @param ISearch $searchObject
         * @return bool
         */
        public function exists( ISearch $searchObject );
        /**
         * @param ISearch $searchObject
         * @return int
         */
        public function count( ISearch $searchObject );
        /**
         * @param AEntity $entity
         * @return void
         */
        public function save( AEntity $entity );
        /**
         * @param AEntity $entity
         * @return void
         */
        public function remove( AEntity $entity );
        /**
         * @param ISearch $searchObject
         * @return void
         */
        public function deleteWhere( ISearch $searchObject );
    }
}
