<?php
/**
 * Utilites class for the progress estimator.
 *
 * @package PHPEstimator\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PHPEstimator;

class ProgressEstimatorUtils
{

	/**
	 * Merge user defined arguments into defaults array.
	 *
	 * @param string|array|object $args     Value to merge with $defaults.
	 * @param array               $defaults Optional. Array that serves as the defaults. Default empty.
	 * @return array Merged user defined values with defaults.
	 */
	public static function parseArgs($args, $defaults)
	{
		// From WordPress core.
		if (is_object($args)) {
			$r = get_object_vars($args);
		} elseif (is_array($args)) {
			$r =& $args;
		}

		if (is_array($defaults)) {
			return array_merge($defaults, $r);
		}

		return $r;
	}

	/**
	 * Gets the current time in milliseconds.
	 *
	 * @return int
	 */
	public static function currentTime()
	{
		return $milliseconds = (int) round(microtime(true) * 1000);
	}

	/**
	 * Converts milliseconds to microtime.
	 *
	 * @param  int $milliseconds Number of milliseconds.
	 * @return int
	 */
	public static function msToMicrotime($milliseconds)
	{
		return $milliseconds * 1000;
	}

	/**
	 * Sleeps execution the supplied number of milliseconds.
	 *
	 * @param  int $milliseconds Number of milliseconds.
	 * @return int
	 */
	public static function sleep($milliseconds)
	{
		usleep(self::msToMicrotime($milliseconds));
	}

	/**
	 * Takes a time span given in seconds and formats it for display. The
	 * returned string will be in MM:SS form.
	 *
	 * @param int  $time The time span in seconds to format.
	 * @return string  The formatted time span.
	 */
	public function formatTime($time)
	{
		// From https://github.com/wp-cli/php-cli-tools/blob/master/lib/cli/Notify.php
		return floor($time / 60) . ':' . str_pad($time % 60, 2, 0, STR_PAD_LEFT);
	}

}
