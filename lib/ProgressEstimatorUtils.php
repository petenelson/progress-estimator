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
}
