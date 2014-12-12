<?php
    /**
     * Xing-Repository Usage Example Mapper
     *
     * @copyright 2013 Kevin K. Nelson (xingcreative.com)
     * Licensed under the MIT license
     */
    namespace Example\Mapper {

        use Xing\Mapping\Platform\ASqlMapper;
        use Xing\Mapping\PropertyMap\APropertyMap;
        use Xing\Mapping\PropertyMap\PropertyMap;

        class UserMapper extends ASqlMapper {
            public function getTableName() {
                return 'User';
            }
            public function getPrimaryKey() {
                return 'Id';
            }
            /**
             * @return string
             */
            public function getTableAlias() {
                return 'U';
            }

            /**
             * @return APropertyMap[]
             */
            public function getPropertyMap() {
                return array(
                    'Id'                => PropertyMap::column('Id')->asInt(),
                    'Email'             => PropertyMap::column('Email'),
                    'Username'          => PropertyMap::column('Username'),
                    'StoredPassword'    => PropertyMap::column('StoredPassword')
                );
            }

        }
    }