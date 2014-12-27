<?php

    namespace Modules\Authentication\Model {
        use Xing\Models\Entity\AEntity;
        use Xing\System\DateTime\DateTime;

        class LoginFailure extends AEntity {
            public $IpAddress;
            /** @var DateTime */
            public $StartDateTime;
            /** @var int */
            public $FailureCount;

            public function __construct() {
                $this->StartDateTime        = DateTime::now();
                $this->FailureCount         = 0;
            }
        }
    }