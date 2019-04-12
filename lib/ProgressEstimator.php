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
	private $currentTime = 0;

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
		$this->items = array_fill(0, $this->total, false);

		if (true === $this->args['auto_start']) {
			$this->start();
		}
	}

	/**
	 * Sets the start time.
	 *
	 * @return void
	 */
	public function start()
	{
		$this->startTime = ProgressEstimatorUtils::currentTime();
		$this->currentTime = $this->startTime;
	}

	/**
	 * Increments the total number of items processed and stores the
	 * execution time of the current item in the items array.
	 *
	 * @return void
	 */
	public function tick()
	{
		// Start the estimator if it wasn't started automatically.
		if (0 === $this->startTime) {
			$this->start();
		}

		if ($this->count < $this->total) {
			// Get the new current time.
			$time = ProgressEstimatorUtils::currentTime();

			// Flag how long this item took to process.
			$this->items[$this->count] = $time - $this->currentTime;

			$this->count++;
			$this->currentTime = $time;
		}
	}

	/**
	 * Gets the estimated number of milliseconds left.
	 *
	 * @return int
	 */
	public function timeLeft()
	{
		$items_left = $this->total - $this->count;
		return intval(ceil($items_left * $this->timePerItem()));
	}

	/**
	 * Gets the elapsed number of milliseconds since the estimator started.
	 *
	 * @return int
	 */
	public function elapsedTime()
	{
		return ProgressEstimatorUtils::currentTime() - $this->startTime ;
	}

	/**
	 * Gets the total number of processing time in milliseconds for all
	 * of the items.
	 *
	 * @return int
	 */
	public function totalProcessingTime()
	{
		return array_sum($this->items);
	}

	/**
	 * Gets the execution time per item in milliseconds. Defaults to an
	 * overall average.
	 *
	 * @return int
	 */
	public function timePerItem()
	{
		if ($this->count > 0) {
			return array_sum($this->items) / $this->count;
		} else {
			return 0;
		}
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
		ProgressEstimatorUtils::formatTime($time);
	}
}
