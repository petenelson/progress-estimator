# Progress Estimator
PHP library to estimate time remaining for a list of tasks.

[![Build Status](https://travis-ci.org/petenelson/progress-estimator.svg?branch=master)](https://travis-ci.org/petenelson/progress-estimator) [![Percentage of issues still open](http://isitmaintained.com/badge/open/petenelson/progress-estimator.svg)](http://isitmaintained.com/project/petenelson/progress-estimator "Percentage of issues still open")

When processing a batch of items that takes a long time to run, this library can be used to to calculate and display the estimated time remaining.

```
Processing 12 items
[1/12]: Bacon ipsum dolor amet (0:35)
[2/12]: Cow porchetta labore shankle (0:33)
[3/12]: Filet mignon porchetta eiusmod tri-tip (0:28)
[4/12]: Venison aliqua, ad brisket pariatur (0:22)
[5/12]: Turkey reprehenderit picanha (0:18)
[6/12]: Turducken fatback ground round (0:16)
[7/12]: Strip steak leberkas laborum (0:12)
[8/12]: Pork belly excepteur buffalo (0:09)
[9/12]: ham chuck ipsum nostrud jerky (0:07)
[10/12]: Rump shank jalapeno (0:05)
[11/12]: Pancetta chicken do spare ribs, (0:02)
[12/12]: Meatball tenderloin picanha (0:00)
````

## Installation and Usage
This package can be installed via composer.
```
composer require petenelson/progress-estimator
```

Here is some example code below, and be sure to check the [examples.php file](examples.php) for a working implementation.

```
require_once __DIR__ . '/vendor/autoload.php';

$items = get_large_list_of_items();
$count = count($items);

// Create the progress estimator.
$estimator = new \PHPEstimator\ProgressEstimator($count);

// Loop through the list of items to process.
for ($i=0; $i < $count; $i++) {

	// Perform some work on each item.
	some_long_running_process_here($items[$i]);

	// Increments the counter and saves the execution time of that item.
	$estimator->tick();

	// Display the current item processed and estimated time remaining.
	$output = sprintf(
		'Processed: %1$s (%2$s)' . PHP_EOL,
		$i,
		$estimator->formatTime($estimator->timeLeft())
	);

	echo $output;
}
```
