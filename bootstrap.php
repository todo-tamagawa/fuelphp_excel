<?php
/**
 * FuelPHP Excel package.
 *
 * @package    Excel
 * @version    1.0
 * @author     Muhittin Özer
 * @link       http://www.muhittinozer.com
 */

/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', __DIR__.DS);
	require(PHPEXCEL_ROOT . 'PHPExcel'.DS.'Autoloader.php');
}

Autoloader::add_classes(array(
	'PHPExcel' => __DIR__.DS.'PHPExcel.php'
));

/*
 * PHP 8 compatibility for PHPExcel (no longer maintained).
 * Fuel's error handler turns every error into an exception, so errors raised inside
 * PHPExcel that do not change its behaviour are swallowed here:
 *  - @-suppressed errors (on PHP 8, error_reporting() is no longer 0 inside @)
 *  - deprecations
 *  - "Trying to access array offset on ..." warnings (silent before PHP 7.4, still yields null)
 * Everything else, and every error outside PHPExcel, is passed to the previous handler.
 */
call_user_func(function () {
	$root = realpath(__DIR__).DIRECTORY_SEPARATOR;
	$previous = null;
	$previous = set_error_handler(function ($severity, $message, $file = '', $line = 0) use ($root, &$previous) {
		if (strpos((string) $file, $root) === 0)
		{
			$suppressed = ! (error_reporting() & $severity);
			$deprecated = (bool) ($severity & (E_DEPRECATED | E_USER_DEPRECATED));
			$null_offset = $severity === E_WARNING && strpos($message, 'Trying to access array offset on') === 0;
			if ($suppressed or $deprecated or $null_offset)
			{
				return true;
			}
		}
		return $previous ? call_user_func($previous, $severity, $message, $file, $line) : false;
	});
});

/*
 * End of file bootstrap.php
*/