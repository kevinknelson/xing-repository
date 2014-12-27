<?php

    namespace Modules\Authentication\Model\Search {
        use Modules\Authentication\Model\User;
        use Xing\Models\Search\AIntelliSearch;

        /**
         * Class UserSearch
         * @package Modules\Authentication\Model\Search
         *
         * @property UserSearch $Id
         * @property UserSearch $Email
         * @property UserSearch $CreatedDateTime
         * @property UserSearch $UpdatedDateTime
         */
        class UserSearch extends AIntelliSearch {
            protected function defineProperties() {
                $this->_properties->addRange( array('Id','Email','CreatedDateTime','UpdatedDateTime') );
            }
            public function getModelInstance() {
                return new User();
            }
        }
    }