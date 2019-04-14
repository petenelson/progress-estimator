<?php
/**
 * Example code for unit tests.
 *
 * @package PHPEstimator\PackageEstimator
 */

// phpcs:disable Generic.WhiteSpace.DisallowTabIndent

require_once __DIR__ . '/vendor/autoload.php';

// A list of items that will be processed.
$items = [
	'Bacon ipsum dolor amet',
	'Cow porchetta labore shankle',
	'Filet mignon porchetta eiusmod tri-tip',
	'Venison aliqua, ad brisket pariatur',
	'Turkey reprehenderit picanha',
	'Turducken fatback ground round',
	'Strip steak leberkas laborum',
	'Pork belly excepteur buffalo',
	'ham chuck ipsum nostrud jerky',
	'Rump shank jalapeno',
	'Pancetta chicken do spare ribs,',
	'Meatball tenderloin picanha',
];

// Display the current item processed and estimated time remaining.
$output = sprintf(
	'Processing %1$d items' . PHP_EOL,
	count($items)
);

echo $output;

// Create the progress estimator.
$estimator = new \PHPEstimator\ProgressEstimator(count($items));

// Loop through the list of items to process.
foreach ($items as $item) {
	// Simulate a process that takes a little while to run. In an actual
	// application, this may be something such as a large database update,
	// remote API request, image processing, etc.
	$sleep_time = rand(800, 4000);
	\PHPEstimator\ProgressEstimatorUtils::sleep($sleep_time);

	// Increments the counter and saves the execution time of thar item.
	$estimator->tick();

	// Display the current item processed and estimated time remaining.
	$output = sprintf(
		'[%3$d/%4$d]: %1$s (%2$s)' . PHP_EOL,
		$item,
		$estimator->formatTime($estimator->timeLeft()),
		$estimator->count,
		$estimator->total
	);

	echo $output;
}
