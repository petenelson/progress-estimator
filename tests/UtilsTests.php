<?php
/**
 * Unit tests for the progress estimator utils class.
 *
 * @package PHPEstimator\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

namespace PHPEstimator\ProgressEstimatorTests;

use PHPUnit\Framework\TestCase;

class UtilsTests extends TestCase
{

	public function testParseArgs()
	{
		$args = [
			'value1' => 'xyz',
			'value4' => 'hello world',
		];

		$defaults = [
			'value1' => 'abc',
			'value2' => true,
			'value3' => 123,
		];

		$args = \PHPEstimator\ProgressEstimatorUtils::parseArgs($args, $defaults);

		$this->assertSame($args['value1'], 'xyz');
		$this->assertSame($args['value2'], true);
		$this->assertSame($args['value3'], 123);
		$this->assertSame($args['value4'], 'hello world');

		// Test args with an object.
		$args = new \stdClass();
		$args->value1 = 'xyz';
		$args->value4 = 'hello world';

		$args = \PHPEstimator\ProgressEstimatorUtils::parseArgs($args, $defaults);

		$this->assertSame($args['value1'], 'xyz');
		$this->assertSame($args['value2'], true);
		$this->assertSame($args['value3'], 123);
		$this->assertSame($args['value4'], 'hello world');
	}

	public function testTime()
	{
		$time_ms   = (int) round(microtime(true) * 1000);
		$util_time = \PHPEstimator\ProgressEstimatorUtils::currentTime();

		$this->assertGreaterThanOrEqual($time_ms, $util_time);
	}
}
