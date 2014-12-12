<?php
/**
 * @package Xing\Mapping
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping {
    use Xing\Mapping\Sql\ISqlQuery;
    use Xing\Models\Entity\AEntity;
    use Xing\Models\Search\ISearch;
    use Xing\System\Locator;

    /**
     * @property ISqlQuery $SqlQuery
     */
    class MappingRepository implements IRepository {
        /**
         * @param $modelObject
         * @return IRepository
         * @throws \Exception
         */
        public static function getMapper( $modelObject ) {
            $namespace    = get_class($modelObject);
            $mapperKey    = $namespace.'\Mapper';
            if( Locator::isDefined($mapperKey) ) {
                return Locator::get($mapperKey);
            }
            else {
                $arr            = explode('\\',$namespace);
                $modelName      = end($arr);
                $testNamespace  = "{$namespace}\\Mapper\\{$modelName}Mapper";
                Locator::defineService($mapperKey, $testNamespace);
                return Locator::get($mapperKey);
            }
        }
        public function search( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
            return $mapper->search($searchObject);
        }
        public function exists( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
            return $mapper->exists($searchObject);
        }
        public function count( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
            return $mapper->count($searchObject);
        }
        public function deleteWhere( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
            $mapper->deleteWhere($searchObject);
        }
        public function save( AEntity $entity ) {
            $mapper     = $this->getMapper($entity);
            $mapper->save($entity);
        }
        public function remove( AEntity $entity ) {
            $mapper     = $this->getMapper($entity);
            $mapper->remove($entity);
        }
    }
}