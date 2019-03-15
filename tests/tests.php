<?php

namespace PeteNelson\ProgressEstimatorTests;

use PHPUnit\Framework\TestCase;

class BasicTests extends TestCase {

	public function test_start_time() {

		$time = time();

		$estimator = new \PeteNelson\ProgressEstimator();

		$this->assertSame( $time, $estimator->start_time );
	}
}
