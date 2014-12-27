<?php

    namespace Modules\Authentication\Helpers {
        class HashHelper {
            private $_hashAlgorithm;
            private $_keyBytes;
            private $_iterations;
            private $_saltPrefix;

            const HashSha1          = 0;
            const HashSha256        = 1;
            const HashSha512        = 2;
            private $_algorithms    = array(
                'sha1','sha256','sha512'
            );

            public function __construct( $algorithm=self::HashSha512, $keyBytes=16, $iterationsInThousands=65 ) {
                $this->_keyBytes            = $keyBytes;
                $this->_iterations          = $iterationsInThousands;
                $this->_hashAlgorithm       = $algorithm;

                if( !in_array($this->_hashAlgorithm,array(self::HashSha1,self::HashSha256,self::HashSha512)) ) {
                    throw new \Exception("Invalid Hashing algorithm provided to HashHelper.");
                }
                $this->_saltPrefix      = $this->setHexByteLength(dechex($keyBytes),1)
                    . $this->setHexByteLength(dechex($this->_hashAlgorithm),1)
                    . $this->setHexByteLength(dechex($this->_iterations),1);
            }
            public function getSalt() {
                return strtoupper($this->_saltPrefix.bin2hex(openssl_random_pseudo_bytes($this->_keyBytes-3)));
            }
            public function hashPassword( $password ) {
                $salt = $this->getSalt();
                return $salt . strtoupper($this->pbkdf2($this->_algorithms[$this->_hashAlgorithm],$password,$salt,$this->_iterations*1000,$this->_keyBytes));
            }
            public function isPasswordValid( $password, $hash ) {
                $keyBytes           = hexdec(substr($hash,0,2));
                $saltLength         = $keyBytes * 2;
                $algorithm          = $this->_algorithms[hexdec(substr($hash,2,2))];
                $iterations         = hexdec(substr($hash,4,2))*1000;
                $salt               = substr($hash,0,$saltLength);
                $passwordHash       = substr($hash,$saltLength);
                $newHash            = strtoupper(self::pbkdf2($algorithm,$password,$salt,$iterations,$keyBytes));
                return self::areHashesEqual($passwordHash,$newHash );
            }
            private function setHexByteLength( $hex, $byteLength=1 ) {
                $expectedLength = $byteLength * 2;
                if( strlen($hex) > $expectedLength ) {
                    throw new \Exception("Settings for password hashing will not work as they are causing the number of bytes allocated to be exceeded");
                }
                while( strlen($hex) < $expectedLength ) {
                    $hex = '0'.$hex;
                }
                return $hex;
            }
            public static function areHashesEqual( $hash1, $hash2 ) {
                $length1    = strlen($hash1);
                $length2    = strlen($hash2);
                $diff       = 0;
                if( $length1 != $length2 ) { return false; }

                for($i = 0; $i < $length1 && $i < $length2; $i++)
                {
                    $diff |= ord($hash1[$i]) ^ ord($hash2[$i]);
                }
                return $diff === 0;
            }
            /*
             * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
             * $algorithm - The hash algorithm to use. Recommended: SHA256
             * $password - The password.
             * $salt - A salt that is unique to the password.
             * $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
             * $key_length - The length of the derived key in bytes.
             * $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
             * Returns: A $key_length-byte key derived from the password and salt.
             *
             * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
             *
             * This implementation of PBKDF2 was originally created by https://defuse.ca
             * With improvements by http://www.variations-of-shadow.com
             */
            private static function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
            {
                $algorithm = strtolower($algorithm);
                if(!in_array($algorithm, hash_algos(), true))
                    trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
                if($count <= 0 || $key_length <= 0)
                    trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);

                if ( function_exists("hash_pbkdf2")) {
                    // The output length is in NIBBLES (4-bits) if $raw_output is false!
                    if (!$raw_output) {
                        $key_length = $key_length * 2;
                    }
                    return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
                }

                $hash_length = strlen(hash($algorithm, "", true));
                $block_count = ceil($key_length / $hash_length);

                $output = "";
                for($i = 1; $i <= $block_count; $i++) {
                    // $i encoded as 4 bytes, big endian.
                    $last = $salt . pack("N", $i);
                    // first iteration
                    $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
                    // perform the other $count - 1 iterations
                    for ($j = 1; $j < $count; $j++) {
                        $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
                    }
                    $output .= $xorsum;
                }

                $result = substr($output, 0, $key_length);
                return $raw_output ? $result : bin2hex($result);
            }
        }
    }