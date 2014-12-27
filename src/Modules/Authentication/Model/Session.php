<?php

    namespace Modules\Authentication\Model {
        use Xing\Models\Entity\AEntity;
        use Xing\System\Collections\Dictionary;
        use Xing\System\DateTime\DateTime;
        use Xing\System\Guid;

        class Session extends AEntity {
            /** @var string */
            public $Key;
            /** @var string */
            public $IpAddress;
            /** @var string */
            public $UserAgent;
            /** @var DateTime */
            public $CreatedDateTime;
            /** @var DateTime */
            public $LastLoggedDateTime;
            /** @var bool */
            public $IsBoundToIp;
            /** @var bool */
            public $IsNonExpiring;
            /** @var int */
            public $UserId;
            /** @var Dictionary */
            public $Vars;

            public function __construct() {
                $this->Key                  = Guid::create()->Value;
                $this->IsBoundToIp          = false;
                $this->IsNonExpiring        = false;
                $this->CreatedDateTime      = DateTime::now();
                $this->LastLoggedDateTime   = DateTime::now();
                $this->UserAgent            = $_SERVER['HTTP_USER_AGENT'];
                $this->IpAddress            = $_SERVER['REMOTE_HOST'];
                $this->Vars                 = new Dictionary();
            }

            /**
             * CAREFUL: a non-boolean value with the bitwise operators could cause bugs
             * @return bool|int
             */
            public function isValidSession() {
                $isValid    = true;
                $isValid   &= !$this->IsBoundToIp   ? true : $this->IpAddress == $_SERVER['REMOTE_HOST'];
                $isValid   &= $this->IsNonExpiring  ? true : $this->CreatedDateTime > DateTime::now()->addWeeks(-2);
                $isValid   &= $this->UserAgent == $_SERVER['HTTP_USER_AGENT'];
                return $isValid;
            }
            public function storeVar( $key, $value ) {
                $this->Vars->add($key,$value);
            }
            public function getVar( $key ) {
                return $this->Vars->getValueOrDefault($key);
            }
        }
    }