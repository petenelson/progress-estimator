<?php
/**
 * Unit tests for the progress estimator.
 *
 * @package PHPEstimator\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PHPEstimator\ProgressEstimatorTests;

use PHPUnit\Framework\TestCase;

class EstimatorTests extends TestCase
{

	public function testStartTime()
	{

		$time = (int) round(microtime(true) * 1000);

		$estimator = new \PHPEstimator\ProgressEstimator();
		$this->assertGreaterThanOrEqual($time, $estimator->currentTime);

		// Test autostart.
		$estimator = new \PHPEstimator\ProgressEstimator(0, ['auto_start' => false]);
		$this->assertSame(0, $estimator->currentTime);

		$estimator->tick();
		$this->assertGreaterThan(0, $estimator->currentTime);
	}

	public function testTotalAndCount()
	{
		$estimator = new \PHPEstimator\ProgressEstimator(10);
		$this->assertSame(10, $estimator->total);

		$estimator->tick();
		$estimator->tick();
		$estimator->tick();

		$this->assertSame(3, $estimator->count);

		$estimator->tick();
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();

		$this->assertSame(8, $estimator->count);

		// This set should stop at 10.
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();

		$this->assertSame(10, $estimator->count);
	}

	public function testItems()
	{
		$count = 10;
		$sleep_time = 100;

		$estimator = new \PHPEstimator\ProgressEstimator($count);
		$this->assertCount($count, $estimator->items);

		// Verify they are all set to false.
		array_map(
			function ($item) {
				$this->assertSame(false, $item);
			},
			$estimator->items
		);

		// Now tick and verify they are all set.
		for ($i = 0; $i < $count; $i++) {
			\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
			$estimator->tick();
		}

		array_map(
			function ($item) {
				$this->assertGreaterThan(0, $item);
			},
			$estimator->items
		);

		array_map(
			function ($item) use ($sleep_time) {
				$this->assertGreaterThanOrEqual($sleep_time, $item);
				$this->assertLessThan($sleep_time + 20, $item);
			},
			$estimator->items
		);
	}

	public function testTimePerItem()
	{
		// Tell it there are no items.
		$estimator = new \PHPEstimator\ProgressEstimator(0);
		$this->assertSame(0, $estimator->timePerItem());

		// Tell it there are ten items.
		$estimator = new \PHPEstimator\ProgressEstimator(10);

		// Manually set the processing time for the first four.
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();
		$estimator->tick();

		$estimator->items[0] = 100;
		$estimator->items[1] = 200;
		$estimator->items[2] = 300;
		$estimator->items[3] = 400;

		$this->assertSame(250, $estimator->timePerItem());

		$this->assertSame(1000, $estimator->totalProcessingTime());
	}

	public function testEstimatedTime()
	{
		$sleep_time = 100;

		// Tell it there are ten items.
		$estimator = new \PHPEstimator\ProgressEstimator(10);

		// If it takes 100ms per item, then it should take 1000ms total.
		// After three items, there should be about 700ms left.
		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
		$estimator->tick();
		
		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
		$estimator->tick();

		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
		$estimator->tick();

		$time_left = $estimator->timeLeft();

		$this->assertGreaterThanOrEqual(700, $time_left);
		$this->assertLessThanOrEqual(750, $time_left);

		// Lets increase the processing time and tick a few more.
		$sleep_time += 100;

		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
		$estimator->tick();

		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
		$estimator->tick();

		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);
		$estimator->tick();

		// First three took 100ms and the next three took 200ms.
		// That's an average time of (300 + 600) / 6 = 150.
		$this->assertGreaterThanOrEqual(900, $estimator->totalProcessingTime());
		$this->assertLessThan(950, $estimator->totalProcessingTime());
		$this->assertGreaterThanOrEqual(150, $estimator->timePerItem());
		$this->assertLessThan(160, $estimator->timePerItem());
	}
}
