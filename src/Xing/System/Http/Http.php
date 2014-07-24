<?php

    namespace Xing\System\Http {
        class Http {
			private static $_basePath	= null;

			public static function configure( $siteRoot ) {
				$siteRoot			= realpath($siteRoot);
				$docRoot			= realpath($_SERVER['DOCUMENT_ROOT']);
				self::$_basePath	= str_replace($docRoot,'',$siteRoot);
			}
			public static function basePath( $url=null ) {
				if( !empty($url) ) {
					return str_replace('~',self::$_basePath,$url);
				}
				return self::$_basePath;
			}
			public static function isOldIE() {
				return preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']);
			}
            public static function isPostBack() {
                return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
            }
            public static function header($name) {
                if( !function_exists('apache_request_headers') ) { return null; }
                $headers    = apache_request_headers();
                foreach( $headers AS $key => $value ) {
                    if( strtolower($key) == strtolower($name) ) {
                        return $value;
                    }
                }
                return null;
            }
            public static function get($name,$default=NULL) {
                return isset($_GET[$name]) ? $_GET[$name] : $default;
            }
            public static function post($name, $default=NULL) {
                return isset($_POST[$name]) ? $_POST[$name] : $default;
            }
            public static function request($name, $default=NULL) {
                return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
            }
            public static function cookie($name, $default=NULL) {
                return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
            }
            public static function server($name, $default=NULL) {
                return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
            }
			public static function session($name, $default=null) {
                return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
			}
            public static function redirect( $uri ) {
				$uri	= str_replace('~',self::basePath(),$uri);
                if( !headers_sent() ) {
                    header("Location: {$uri}");
                }
                else {
                    echo("<div class='showonlythis'>We are attempting to redirect you.<br />
                        If you do not get redirected immediately, JavaScript may be disabled.<br />
                        Please <a href='{$uri}'>click here</a> to continue.</div>");
                    echo("<script type='text/javascript'>window.location='{$uri}'</script>");
                }
                exit;
            }
			public static function getSelfPostLink( $getParams, array $removeKeys=array() ) {
				$var 		= explode('?',$_SERVER['REQUEST_URI']);
				$uri		= reset( $var );
				return self::linkPreserved($uri, $getParams, $removeKeys);
			}
			public static function linkPreserved( $uri, $getParams='', array $removeKeys=array() ) {
				$getClone	= $_GET;
				parse_str($getParams, $newVars);
				foreach( $newVars AS $key => $value ) {
					$getClone[$key] = $value;
				}
				foreach( $removeKeys AS $key ) {
					unset($getClone[$key]);
				}
				$finalQuery	= http_build_query($getClone);
				return str_replace('~',Http::basePath(),$uri).(empty($finalQuery) ? '' : '?'.$finalQuery);
			}
            public static function getParamsString( $prefix='?' ) {
                $parts      = explode('?',$_SERVER['REQUEST_URI']);
                return empty($parts[1]) ? '' : $prefix.$parts[1];
            }
        }
    }
