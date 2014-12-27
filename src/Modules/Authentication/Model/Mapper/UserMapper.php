<?php

    namespace Modules\Authentication\Model\Mapper {
        use Xing\Mapping\Platform\ASqlMapper;
        use Xing\Mapping\PropertyMap\APropertyMap;
        use Xing\Mapping\PropertyMap\PropertyMap;

        class UserMapper extends ASqlMapper {
            public function getTableName() {
                return 'login_user';
            }
            public function getPrimaryKey() {
                return 'user_idx';
            }
            public function getTableAlias() {
                return 'U';
            }

            /**
             * @return APropertyMap[]
             */
            public function getPropertyMap() {
                return array(
                    'Id'                    => PropertyMap::column('user_idx')->asInt(),
                    'FullName'              => PropertyMap::column('user_fullname'),
                    'Email'                 => PropertyMap::column('user_email'),
                    'StoredPassword'        => PropertyMap::column('user_password')->asHex(),
                    'PasswordSalt'          => PropertyMap::column('user_password_salt')->asHex(),
                    'OneTimeSalt'           => PropertyMap::column('user_onetime_salt')->asHex(),
                    'CreatedDateTime'       => PropertyMap::column('user_created_utc')->asDateTime(),
                    'UpdatedDateTime'       => PropertyMap::column('user_updated_utc')->asDateTime(),
                    'FailedLoginDateTime'   => PropertyMap::column('user_login_fail_start_utc')->asDateTime(),
                    'FailedLoginCount'      => PropertyMap::column('user_login_fail_count')->asInt()
                );
            }
        }
    }