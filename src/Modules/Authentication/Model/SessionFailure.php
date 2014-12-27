<?php

    namespace Modules\Authentication\Model {
        use Xing\Models\Entity\AEntity;
        use Xing\System\DateTime\DateTime;

        class SessionFailure extends AEntity {
            public $IpAddress;
            /** @var DateTime */
            public $StartDateTime;
            /** @var int */
            public $FailureCount;
        }
    }