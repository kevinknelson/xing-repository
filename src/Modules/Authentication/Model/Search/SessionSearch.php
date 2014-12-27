<?php

    namespace Modules\Authentication\Model\Search {
        use Modules\Authentication\Model\Session;
        use Xing\Models\Search\AIntelliSearch;

        /**
         * Class SessionSearch
         * @package Modules\Authentication\Model\Search
         *
         * @property SessionSearch $Id
         */
        class SessionSearch extends AIntelliSearch {
            protected function defineProperties() {
                $this->_properties->addRange( array('Id') );
            }
            public function getModelInstance() {
                return new Session();
            }
        }
    }