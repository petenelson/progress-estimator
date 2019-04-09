<?php
/**
 * Estimator class to get estimated time left.
 *
 * @package PeteNelson\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PeteNelson;

class ProgressEstimator
{

	public $total      = 0;
	public $count      = 0;
	public $startTime  = 0;
	public $args       = [];

	/**
	 * Creates a new Estimator class.
	 *
	 * @param int   $total The total number of items for estimating.
	 * @param array $args  List of additional arguments.
	 */
	public function __construct($total = 0, $args = [])
	{
		$this->total      = $total;
		$this->count      = 0;
		$this->startTime  = time();

		$this->args = ProgressEstimatorUtils::parseArgs(
			$args,
			[]
		);
	}

	/**
	 * Increments the total number of items processed.
	 *
	 * @return void
	 */
	public function tick()
	{
		if ($this->count < $this->total) {
			$this->count++;
		}
	}

	/**
	 * Gets the estimated number of seconds left.
	 *
	 * @return int
	 */
	public function secondsLeft()
	{
		$per_second = $this->per_second();
		$items_left = $this->total - $this->count;
		return intval(ceil($items_left * $per_second));
	}

	/**
	 * Gets the elapsed number of seconds since the class was created.
	 *
	 * @return int
	 */
	public function elapsedSeconds()
	{
		return time() - $this->startTime ;
	}

	/**
	 * Gets the number of items processed per second.
	 *
	 * @return float
	 */
	public function perSecond()
	{
		$per_second = floatval(0);
		$elapsed = $this->elapsed_seconds();
		if ($elapsed > 0 && $this->count > 0) {
			$per_second = round($this->count / $elapsed, 1);
		}

		return $per_second;
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
