<?php

namespace Xing\System {
	class Color extends APropertiedObject {
		private $_red;
		private $_green;
		private $_blue;

		private function __construct($r, $g, $b) {
			$this->_red		= $r;
			$this->_green	= $g;
			$this->_blue	= $b;
		}
		#region -- CHAINABLE
		public static function fromHex( $hex ) {
			$hex	= str_replace('#','',$hex);
			if( strlen($hex) == 3 ) {
				$red	= hexdec(substr($hex,0,1).substr($hex,0,1));
				$green	= hexdec(substr($hex,1,1).substr($hex,1,1));
				$blue	= hexdec(substr($hex,2,1).substr($hex,2,1));
			}
			elseif( strlen($hex) == 6 ) {
				$red	= hexdec(substr($hex,0,2));
				$green	= hexdec(substr($hex,2,2));
				$blue	= hexdec(substr($hex,4,2));
			}
			else {
				throw new \Exception('Invalid Hex value used to create color');
			}
			return new self($red, $green, $blue);
		}
		public static function fromRgb( $r, $g, $b ) {
			return new self($r, $g, $b);
		}
		public function changeLightness( $percent ) {
			// Work out if hash given
			$hex = $this->getHex();
			if (stristr($hex,'#')) {
				$hex = str_replace('#','',$hex);
			}
			/// HEX TO RGB
			$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
			//// CALCULATE
			for ($i=0; $i<3; $i++) {
				// See if brighter or darker
				if ($percent > 0) {
					// Lighter
					$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
				} else {
					// Darker
					$positivePercent = $percent - ($percent*2);
					$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
				}
				// In case rounding up causes us to go to 256
				if ($rgb[$i] > 255) {
					$rgb[$i] = 255;
				}
			}
			//// RBG to Hex
			$hex = '';
			for($i=0; $i < 3; $i++) {
				// Convert the decimal digit to hex
				$hexDigit = dechex($rgb[$i]);
				// Add a leading zero if necessary
				if(strlen($hexDigit) == 1) {
					$hexDigit = "0" . $hexDigit;
				}
				// Append to the hex string
				$hex .= $hexDigit;
			}
			return self::fromHex($hex);
		}
		private function getValidRgbRange($number) {
			return $number > 255 ? 255 : ($number < 0 ? 0 : $number);
		}
		#endregion

		public function getHex() {
			return str_pad(dechex($this->_red), 2, '0', STR_PAD_LEFT)
				. str_pad(dechex($this->_green), 2, '0', STR_PAD_LEFT)
				. str_pad(dechex($this->_blue), 2, '0', STR_PAD_LEFT);
		}

		/**
		 * returns black or white to contrast color
		 * @return Color
		 */
		public function getContrastColor() {
			return ($this->_red + $this->_green + $this->_blue) > 382 ? self::fromHex('000000') : self::fromHex('FFFFFF');
		}
		public function getDarkerColor( $number ) {
			return self::fromRgb($this->_red,$this->_green,$this->_blue)->changeLightness(-abs($number)/100);
		}
		public function getLighterColor( $number ) {
			return self::fromRgb($this->_red,$this->_green,$this->_blue)->changeLightness(abs($number)/100);
		}
		public function getCssHex() {
			return '#'.$this->getHex();
		}
		public function getCss2Rgb() {
			return "rgb({$this->_red}, {$this->_green}, {$this->_blue})";
		}
		public function getCss3Rgba( $opacity=1 ) {
			return "rgba({$this->_red}, {$this->_green}, {$this->_blue}, $opacity)";
		}

		#region -- GETTERS/SETTERS
		public function get_R() {
			return $this->_red;
		}
		public function get_G() {
			return $this->_green;
		}
		public function get_B() {
			return $this->_blue;
		}
		#endregion
	}
}