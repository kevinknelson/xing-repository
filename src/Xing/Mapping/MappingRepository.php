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
    class MappingRepository extends APropertiedObject implements IRepository {
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
        }
        public function exists( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
        }
        public function count( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
        }
        public function deleteWhere( ISearch $searchObject ) {
            $mapper     = $this->getMapper($searchObject->getModelInstance());
        }
        public function save( AEntity $entity ) {
            $mapper     = $this->getMapper($entity);
        }
        public function remove( AEntity $entity ) {
            $mapper     = $this->getMapper($entity);
            $mapper->remove($entity);
        }
    }
}