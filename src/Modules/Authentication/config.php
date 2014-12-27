<?php

    namespace {
        use Xing\System\Locator;

        Locator::defineServices( array(
            'Modules\Authentication\Model\Session\Mapper'   => 'Modules\Authentication\Model\Mapper\SessionMapper',
            'Modules\Authentication\Model\User\Mapper'      => 'Modules\Authentication\Model\Mapper\UserMapper'
        ));
    }