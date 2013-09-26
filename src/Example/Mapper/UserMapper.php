<?php
    /**
     * Xing-Repository Usage Example Mapper
     *
     * @copyright 2013 Kevin K. Nelson (xingcreative.com)
     * Licensed under the MIT license
     */
    namespace Example\Mapper {
        use Xing\Repository\Sql\AAutoMapper;

        class UserMapper extends AAutoMapper {
            public function getTableName() {
                return 'User';
            }
            public function getPrimaryKey() {
                return 'Id';
            }
        }
    }