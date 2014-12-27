<?php

    namespace Xing\System\Http {
        use Xing\System\AEnum;

        /**
         * Class HttpResponseCode
         * @package Xing\System\Http
         *
         * @method static HttpResponseCode BadRequest()
         * @method static HttpResponseCode Unauthorized()
         * @method static HttpResponseCode PageNotFound()
         * @method static HttpResponseCode InternalServerError()
         * @property-read string $DefaultMessage
         */
        class HttpResponseCode extends AEnum {
            const BadRequest            = 400;
            const NotLoggedIn           = 401;
            const PermissionDenied      = 403;
            const PageNotFound          = 404;
            const InternalServerError   = 500;

            public function get_Description() {
                switch( $this->_value ) {
                    case self::BadRequest:          return '400 Bad Request';
                    case self::NotLoggedIn:         return '401 Unauthorized';
                    case self::PermissionDenied:    return '403 Forbidden';
                    case self::PageNotFound:        return '404 Not Found';

                    case self::InternalServerError:
                    default:                        return '500 Internal Server Error';
                }
            }
            public function get_DefaultMessage() {
                switch( $this->_value ) {
                    case self::BadRequest:          return 'There were errors in the request.';
                    case self::NotLoggedIn:         return 'You are not logged in or your authentication has expired. Please login and try again.';
                    case self::PermissionDenied:    return 'You are not authorized to access the requested data.';
                    case self::PageNotFound:        return 'The page you requested could not be found.';

                    case self::InternalServerError:
                    default:                        return 'There was an unexpected error and your request could not be processed.';
                }
            }
        }
    }