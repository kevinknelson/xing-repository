<?php

    namespace Xing\System\Http {
        use Xing\System\AEnum;

        /**
         * Class HttpResponseCode
         * @package Xing\System\Http
         *
         * @method static HttpResponseCode PageNotFound()
         * @property-read string $DefaultMessage
         */
        class HttpResponseCode extends AEnum {
            const BadRequest    = 400;
            const Unauthorized  = 401;
            const PageNotFound  = 404;

            public function get_Description() {
                switch( $this->_value ) {
                    case self::BadRequest:      return '400 Bad Request';
                    case self::Unauthorized:    return '401 Unauthorized';
                    case self::PageNotFound:    return '404 Not Found';
                    default:                    return '500 Internal Server Error';
                }
            }
            public function get_DefaultMessage() {
                switch( $this->_value ) {
                    case self::BadRequest:      return 'There were errors in the request.';
                    case self::Unauthorized:    return 'You are not authorized to access the requested data.';
                    case self::PageNotFound:    return 'The page you requested could not be found.';
                    default:                    return 'There was an unexpected error and your request could not be processed.';
                }
            }
        }
    }