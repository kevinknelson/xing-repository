<?php

    namespace Xing\System\Exception {
		use Xing\System\Collections\ValidationErrors;

		class ValidationException extends \Exception {
			private $_validationResults;
            public function __construct( ValidationErrors $validationResults ) {
                parent::__construct("Validation Failed");
				$this->_validationResults = $validationResults;
            }
			public function getValidationResults() {
				return $this->_validationResults;
			}
        }
    }
