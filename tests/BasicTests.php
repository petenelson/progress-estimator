<?php
/**
 * Basic unit tests for the progress estimator.
 *
 * @package PeteNelson\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PeteNelson\ProgressEstimatorTests;

use PHPUnit\Framework\TestCase;

class BasicTests extends TestCase
{

	public function testStartTime()
	{
		$time = time();
		$estimator = new \PeteNelson\ProgressEstimator();
		$this->assertSame($time, $estimator->startTime);
	}

	public function testTotalAndCount()
	{
		$estimator = new \PeteNelson\ProgressEstimator(10);
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
