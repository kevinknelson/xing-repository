<?php

    namespace Xing\Mvc {
        use Xing\Repository\IRepository;
        use Xing\System\Collections\DictionaryCast;
        use Xing\System\Http\Uri;
        use Xing\System\Locator;

        abstract class ABaseController {
            /** @var IRepository */
            protected $_repository;
            /** @var Uri */
            protected $_uri;
            /** @var DictionaryCast */
            protected $_data;

            final public function __construct( Uri $uri ) {
                $this->_repository  = Locator::get('IRepository');
                $this->_uri         = $uri;
                $this->_data        = $uri->PostData;
            }
            public function init() {}
        }
    }