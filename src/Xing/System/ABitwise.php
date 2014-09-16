<?php

    namespace Xing\System {
        /**
         * @property-read int $Value
         * @property-read string $Description
         */
        abstract class ABitwise extends AEnum {
            /**
             * @param int $value
             */
            public function __construct( $value=0 ) {
                $this->_value = (int) $value;
            }
			public function contains( $bits ) {
                $bits = $bits instanceof ABitwise ? $bits->Value : $bits;
				return ($this->_value & intval($bits)) > 0;
			}
			public function add( $bits ) {
                $bits = $bits instanceof ABitwise ? $bits->Value : $bits;
				$this->_value |= intval($bits);
			}
            public function addRange( array $multipleBits ) {
                foreach( $multipleBits AS $bits ) {
                    $this->add($bits);
                }
            }
			public function remove( $bits ) {
                $bits = $bits instanceof ABitwise ? $bits->Value : $bits;
				$this->_value &= ~intval($bits);
			}
            public function asSerializable() {
                return $this->_value;
            }
        }
    }
