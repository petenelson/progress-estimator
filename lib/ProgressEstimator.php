<?php
/**
 * Estimator class to get estimated time left.
 *
 * @package PHPEstimator\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PHPEstimator;

class ProgressEstimator
{

	public $total      = 0;
	public $count      = 0;
	public $startTime  = 0;
	public $args       = [];
	public $items      = [];
	private $_index    = 0;

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
		$this->startTime  = 0;

		$this->args = ProgressEstimatorUtils::parseArgs(
			$args,
			[
				'auto_start' => true,
			]
		);

		// Setup the list of items for tracking times.
		array_fill( 0, $this->total, false );

		if (true === $this->args['auto_start']) {
			$this->start();
		}
	}

	/**
	 * Sets the start time.
	 *
	 * @return void
	 */
	public function start() {
		$this->startTime = ProgressEstimatorUtils::currentTime();
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
	 * Gets the estimated number of milliseconds left.
	 *
	 * @return int
	 */
	public function timeLeft()
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
	public function elapsedTime()
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
		$elapsed = $this->elapsedTime();
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
