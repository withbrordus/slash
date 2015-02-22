<?php

namespace Slash;

class Debug {
	private static $enabled = false;

	public static function enabled($errorReportingLevel = E_ALL, $displayErrors = 1) {
		if(self::$enabled) return;

		error_reporting($errorReportingLevel);
		ini_set("display_errors", $displayErrors);
		self::$enabled = true;
		//implement exception handler for logger
	}
} 