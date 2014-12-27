<?php

    namespace Xing\System\Traits {
        use Xing\System\Exception\ReadOnlyPropertyException;
        use Xing\System\Exception\UndefinedPropertyException;
        use Xing\System\Exception\WriteOnlyPropertyException;

        trait PropertiedTrait {
            public function __get( $var ) {
                $method = "get_{$var}";
                if( method_exists($this, $method) ) {
                    return call_user_func(array( $this, $method ));
                }
                elseif( method_exists($this, "set_{$var}") ) {
                    throw new WriteOnlyPropertyException($var);
                }
                else {
                    throw new UndefinedPropertyException($var);
                }
            }
            public function __set( $var, $value ) {
                $method = "set_{$var}";
                if( method_exists($this, $method) ) {
                    call_user_func(array( $this, $method ), $value);
                }
                elseif( method_exists($this, "get_{$var}") ) {
                    throw new ReadOnlyPropertyException($var);
                }
                else {
                    throw new UndefinedPropertyException($var);
                }
            }
            public function __isset( $var ) {
                $getMethod = "get_{$var}";
                if( method_exists($this, $getMethod) ) {
                    $value = call_user_func(array( $this, $getMethod ));
                    return !is_null($value);
                }
                return false;
            }
        }
    }