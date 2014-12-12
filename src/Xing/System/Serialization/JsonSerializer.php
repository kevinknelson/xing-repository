<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Serialization {
	use Xing\System\Collections\Xinq;

	class JsonSerializer {
        public static function encode( $data ) {
            if( version_compare(phpversion(),'5.4.0','>=') ) {
                return json_encode($data);
            }
            else {
                return self::legacyEncode($data);
            }
        }
        protected static function legacyEncode( $data ) {
            if( is_array($data) && is_int(key($data)) ) {
                return '[' . Xinq::join($data, ',', function( $value, $key ) {
                    return JsonSerializer::encode($value);
                }) . ']';
            }
            elseif( is_array($data) ) {
                return '{' . Xinq::join($data, ',', function( $value, $key ) {
                    return json_encode((string) $key) . ':' . JsonSerializer::encode($value);
                }) . '}';
            }
            elseif( is_object($data) ) {
                /** @var $data ISerializable */
                $arr = $data instanceof ISerializable ? $data->asSerializable() : get_object_vars($data);
                return JsonSerializer::encode($arr);
            }
            else {
                return json_encode($data);
            }
        }
    }
}
