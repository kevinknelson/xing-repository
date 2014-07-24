<?php

namespace Xing\System {
    use Xing\System\Http\Http;

    class File {
		public static function getAbsolutePath($path) {
			return $_SERVER['DOCUMENT_ROOT'].Http::basePath($path);
		}
		public static function get( $path ) {
			return file_get_contents( self::getAbsolutePath($path) );
		}
		public static function save( $path, $data ) {
			file_put_contents( self::getAbsolutePath($path), $data );
		}
        public static function append( $path, $data ) {
            $handle         = fopen(self::getAbsolutePath($path),'a');
            if( $handle !== false ) {
                return fwrite($handle,$data) !== false;
            }
            return false;
        }
		public static function saveUploadedFile( $path, $filename ) {
			return move_uploaded_file( $filename, self::getAbsolutePath($path) );
		}
		public static function output( $path ) {
			readfile( self::getAbsolutePath($path) );
		}
		public static function delete( $path ) {
			return unlink( self::getAbsolutePath($path) );
		}
	}
}