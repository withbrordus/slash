<?php

namespace Slash\Http;

class Normalizer {
	static $structure = array('name', 'type', 'tmp_name', 'error', 'size');

	private static $copied = array(),
		$temp;

	public static function normalize($file) {
		if(empty($file) === false && count($file[Normalizer::$structure[0]]) !== 0) {
			self::$copied[] = call_user_func_array(
				function($file) {
					$normal = array();
					foreach (Normalizer::$structure as $piece) {
						$normal[$piece] = array_shift($file[$piece]);
					}
					self::$temp = $file;

					return $normal;
				}
				, array($file));

			return self::normalize(self::$temp);
		}

		return self::$copied;
	}
} 