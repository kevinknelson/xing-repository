<?php

    namespace Modules\Authentication\Model\Mapper {
        use Xing\Mapping\Platform\ASqlMapper;
        use Xing\Mapping\PropertyMap\APropertyMap;
        use Xing\Mapping\PropertyMap\PropertyMap;

        class SessionMapper extends ASqlMapper {
            public function getTableName() {
                return 'session';
            }
            public function getPrimaryKey() {
                return 'session_idx';
            }
            public function getTableAlias() {
                return 'S';
            }

            /**
             * @return APropertyMap[]
             */
            public function getPropertyMap() {
                return array(
                    'Id'                    => PropertyMap::column('session_idx')->asInt(),
                    'Key'                   => PropertyMap::column('session_key')->asHex(),
                    'IpAddress'             => PropertyMap::column('session_ip'),
                    'UserAgent'             => PropertyMap::column('session_user_agent'),
                    'CreatedDateTime'       => PropertyMap::column('session_created_utc')->asDateTime(),
                    'LastLoggedDateTime'    => PropertyMap::column('session_last_logged_utc')->asDateTime(),
                    'IsBoundToIp'           => PropertyMap::column('session_bind_to_ip')->asBool(),
                    'IsNonExpiring'         => PropertyMap::column('session_never_expires')->asBool(),
                    'Vars'                  => PropertyMap::column('session_vars')->asJson(),
                    'UserId'                => PropertyMap::column('user_idx')->asInt()
                );
            }
        }
    }