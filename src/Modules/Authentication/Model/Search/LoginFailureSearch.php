<?php

    namespace Modules\Authentication\Model\Search {
        use Modules\Authentication\Model\LoginFailure;
        use Xing\Models\Search\AIntelliSearch;

        /**
         * Class LoginFailureSearch
         * @package Modules\Authentication\Model\Search
         *
         * @property LoginFailureSearch $IpAddress
         */
        class LoginFailureSearch extends AIntelliSearch {
            protected function defineProperties() {
                $this->_properties->addRange( array('IpAddress') );
            }
            public function getModelInstance() {
                return new LoginFailure();
            }
        }
    }