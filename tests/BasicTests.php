<?php
/**
 * Basic unit tests for the progress estimator.
 *
 * @package PHPEstimator\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PHPEstimator\ProgressEstimatorTests;

use PHPUnit\Framework\TestCase;

class BasicTests extends TestCase
{

	public function testStartTime()
	{

		$time = (int) round(microtime(true) * 1000);

		$estimator = new \PHPEstimator\ProgressEstimator();
		$this->assertGreaterThanOrEqual($time, $estimator->startTime);

		// Test autostart.
		$estimator = new \PHPEstimator\ProgressEstimator(0, ['auto_start' => false]);
		$this->assertSame(0, $estimator->startTime);
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
}
