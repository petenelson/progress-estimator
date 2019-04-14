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

		// Test without defaults.
		$args = \PHPEstimator\ProgressEstimatorUtils::parseArgs($args, 'hello world');

		$this->assertSame($args['value1'], 'xyz');
		$this->assertSame($args['value4'], 'hello world');
		$this->assertTrue(!isset($args['value2']));
		$this->assertTrue(!isset($args['value3']));

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

	public function testTimes()
	{
		$time_ms   = (int) round(microtime(true) * 1000);
		$util_time = \PHPEstimator\ProgressEstimatorUtils::currentTime();

		$this->assertGreaterThanOrEqual($time_ms, $util_time);

		$this->assertSame(2000000, \PHPEstimator\ProgressEstimatorUtils::msToMicrotime(2000));
	}

	public function testSleep()
	{
		$sleep_time = 500;
		$start_time = \PHPEstimator\ProgressEstimatorUtils::currentTime();
		\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);

		$total_ms = \PHPEstimator\ProgressEstimatorUtils::currentTime() - $start_time;

		$this->assertGreaterThanOrEqual($sleep_time, $total_ms);
		$this->assertLessThan($sleep_time + 50, $total_ms);
	}

	public function testFormatTime()
	{
		$estimator = new \PHPEstimator\ProgressEstimator(0);

		$time_left = $estimator->formatTime(10000);
		$this->assertSame('0:10', $time_left);

		$time_left = $estimator->formatTime(70000);
		$this->assertSame('1:10', $time_left);
	}
}
