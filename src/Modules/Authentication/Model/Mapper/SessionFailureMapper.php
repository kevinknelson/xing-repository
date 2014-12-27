<?php

    namespace Modules\Authentication\Model\Mapper {
        use Xing\Mapping\Platform\ASqlMapper;
        use Xing\Mapping\PropertyMap\APropertyMap;
        use Xing\Mapping\PropertyMap\PropertyMap;

        class SessionFailureMapper extends ASqlMapper {
            public function getTableName() {
                return 'session_failure';
            }
            public function getPrimaryKey() {
                return 'session_failure_idx';
            }
            public function getTableAlias() {
                return 'SF';
            }

            /**
             * @return APropertyMap[]
             */
            public function getPropertyMap() {
                return array(
                    'Id'                    => PropertyMap::column('session_failure_idx')->asInt(),
                    'IpAddress'             => PropertyMap::column('session_failure_ip'),
                    'StartDateTime'         => PropertyMap::column('session_failure_start_utc')->asDateTime(),
                    'FailureCount'          => PropertyMap::column('session_failure_count')->asInt(),
                );
            }
        }
    }