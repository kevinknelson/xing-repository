<?php

    namespace Modules\Authentication\Model\Search {
        use Modules\Authentication\Model\SessionFailure;
        use Xing\Models\Search\AIntelliSearch;

        /**
         * Class SessionFailureSearch
         * @package Modules\Authentication\Model\Search
         *
         * @property SessionFailureSearch $IpAddress
         */
        class SessionFailureSearch extends AIntelliSearch {
            protected function defineProperties() {
                $this->_properties->addRange( array('IpAddress') );
            }
            public function getModelInstance() {
                return new SessionFailure();
            }
        }
    }