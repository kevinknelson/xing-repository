<?php

    namespace Modules\Authentication\Model\Mapper {
        use Xing\Mapping\Platform\ASqlMapper;
        use Xing\Mapping\PropertyMap\APropertyMap;
        use Xing\Mapping\PropertyMap\PropertyMap;

        class LoginFailureMapper extends ASqlMapper {
            public function getTableName() {
                return 'login_failure';
            }
            public function getPrimaryKey() {
                return 'failure_idx';
            }
            public function getTableAlias() {
                return 'LF';
            }

            /**
             * @return APropertyMap[]
             */
            public function getPropertyMap() {
                return array(
                    'Id'                    => PropertyMap::column('failure_idx')->asInt(),
                    'IpAddress'             => PropertyMap::column('failure_ip'),
                    'StartDateTime'         => PropertyMap::column('failure_start_utc')->asDateTime(),
                    'FailureCount'          => PropertyMap::column('failure_count')->asInt(),
                );
            }
        }
    }