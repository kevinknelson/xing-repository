<?php
    /**
     * Xing-Repository Usage Example Search Object
     *
     * @copyright 2013 Kevin K. Nelson (xingcreative.com)
     * Licensed under the MIT license
     */
    namespace Example\Search {
        use Example\User;
        use Xing\Repository\AIntelliSearch;

        /**
         * Class UserSearch
         * @package Example\Search
         *
         * @property-read UserSearch $Id
         * @property-read UserSearch $Email
         * @property-read UserSearch $Username
         */
        class UserSearch extends AIntelliSearch {
            public function defineProperties() {
                $this->_properties->addRange(array('Id','Email','Username'));
            }
            public function getModelInstance() {
                return new User();
            }
        }
    }