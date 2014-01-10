<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository\Sql {
    use Xing\Repository\AEntity;
    use Xing\System\Locator;
    use Xing\Repository\IRepository;
    use Xing\Repository\ISearch;
    use Xing\System\APropertiedObject;

    /**
     * @property ISqlQuery $SqlQuery
     */
    class SqlRepository extends APropertiedObject implements  IRepository {
        public function get_SqlQuery() {
            return Locator::getNew('ISqlQuery');
        }
        private function getMapper( AEntity $obj ) {
            $mapperName	= get_class($obj->entity()).'\Mapper';
            /** @var IMapQueries $mapper */
            return Locator::get($mapperName);
        }
        public function search( ISearch $searchObject ) {
            $model		= $searchObject->getModelInstance();
            /** @var IMapQueries $mapper */
            $mapper		= $this->getMapper($model);
            return $mapper->buildQuery( $this->SqlQuery, $searchObject )->getCollection($model,$mapper);
        }
        public function exists( ISearch $searchObject ) {
            return $this->count($searchObject) > 0;
        }
        public function count( ISearch $searchObject ) {
            $model		= $searchObject->getModelInstance();
            /** @var IMapQueries|IDefineTable $mapper */
            $mapper		= $this->getMapper($model);
            $count		= $mapper instanceof IDefineTable ? $mapper->getPrimaryKey() : null;
            return $mapper->buildQuery( $this->SqlQuery, $searchObject )->getCount($count);
        }
        public function save( AEntity $entity ) {
            $mapper		= $this->getMapper($entity);
            if( $mapper instanceof IMapSaveArrays ) {
                /** @var IMapSaveArrays $mapper */
                $this->SqlQuery->save($entity,$mapper);
            }
        }
        public function deleteWhere( ISearch $searchObject ) {
            $model		= $searchObject->getModelInstance();
            /** @var IMapQueries $mapper */
            $mapper		= $this->getMapper($model);
            return $mapper->buildQuery( $this->SqlQuery, $searchObject )->delete();
        }
        public function remove( AEntity $entity ) {
            /** @var $model AEntity */
            $model		= $entity->entity();
            /** @var $mapper IDefineTable */
            $mapper		= $this->getMapper($model);
            $primaryKey = $mapper->getPrimaryKey();
            if( !($mapper instanceof IDefineTable) ) {
                throw new \Exception(get_class($mapper).' must implement IDefineTable');
            }
            return $this->SqlQuery->from( $mapper->getTableName() )->where("{$primaryKey}={0}",$entity->Id ?: -1)->delete();
        }
    }
}