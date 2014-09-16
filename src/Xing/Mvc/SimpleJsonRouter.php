<?php

    namespace Xing\Mvc {
        use Xing\System\Exception\EndUserException;
        use Xing\System\Exception\ErrorHandler;
        use Xing\System\Exception\HttpException;
        use Xing\System\Exception\ValidationException;
        use Xing\System\Format;
        use Xing\System\Http\HttpResponseCode;
        use Xing\System\Http\Uri;
        use Xing\System\Serialization\JsonSerializer;

        class SimpleJsonRouter implements IRouter {
            private $_defaultNs;

            public function __construct() {
                $this->_defaultNs   = '\\';
            }
            public function setDefaultNamespace( $ns ) {
                $this->_defaultNs   = $ns;
            }
            public function run() {
                header('Content-Type: application/json');
                $result             = array('IsSuccess'=>true);

                try {
                    $uri            = new Uri($_SERVER['REQUEST_URI']);
                    $uriParts       = $uri->UriParts;
                    $controllerNs   = $this->_defaultNs . Format::toUpperCamelCase(array_shift($uriParts) . 'Controller');
                    $method         = Format::toLowerCamelCase(array_shift($uriParts) ?: 'index')."Action";
                    /** @var ABaseController $controller */
                    if( !class_exists($controllerNs) || !method_exists($controllerNs,$method) ) {
                        throw new HttpException(HttpResponseCode::PageNotFound());
                    }
                    $controller     = new $controllerNs($uri);
                    $controller->init();

                    $result['Data'] = call_user_func_array(array($controller,$method),$uriParts);
                }
                catch( HttpException $ex ) { // an standard HTTP-coded message, e.g. "404 Not Found" or "401 Unauthorized"
                    header('HTTP/1.0 '.$ex->getHttpResponseCode()->Description);
                    $result         = array(
                        'IsSuccess'     => false,
                        'Message'       => $ex->getHttpMessage()
                    );
                }
                catch( EndUserException $ex ) { // an exception where it's okay for the user to see the message
                    header('HTTP/1.0 '.HttpResponseCode::BadRequest()->Description);
                    $result         = array(
                        'IsSuccess'     => false,
                        'Message'       => $ex->getMessage()
                    );
                }
                catch( ValidationException $ex ) { // user's input did not pass validation
                    header('HTTP/1.0 '.HttpResponseCode::BadRequest()->Description);
                    $result         = array(
                        'IsSuccess'         => false,
                        'Message'           => 'Please Fix the Validation Errors',
                        'ValidationErrors'  => $ex->getValidationResults()
                    );
                }
                catch( \Exception $ex ) { // an exception that shouldn't happen
                    header('HTTP/1.0 '.HttpResponseCode::InternalServerError()->Description);
                    $result         = array(
                        'IsSuccess'     => false,
                        'Message'       => 'An unexpected error occurred. Please try again.'
                    );
                    ErrorHandler::logException($ex);
                }

                try {
                    echo JsonSerializer::encode( $result );
                }
                catch( \Exception $ex ) { // an exception that shouldn't happen
                    header('HTTP/1.0 '.HttpResponseCode::InternalServerError()->Description);
                    ErrorHandler::logException($ex);
                    echo('{"IsSuccess":false,"Message":"An unexpected error occurred. Please try again."}');
                }
            }
        }
    }