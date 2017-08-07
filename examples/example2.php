<?php

include('../classes/Bootstrap.php');

include('./sampleClasses/Distance.php');

use SpyMaster\SpyMaster as SpyMaster;


// Instantiate Distance object with constructor arguments of 10 miles
// This will be converted to metres internally
$distance = new Distance(10, Distance::MILES);

// Create our spy object allowing both read and write
$spy = (new SpyMaster($distance))->infiltrate(SpyMaster::SPY_READ_WRITE);


// Use the spy to read the Distance value property
echo 'Spy reads Distance value as ', $spy->value, PHP_EOL;

// Get the Distance value property using it's getter method for comparison
echo 'Distance is set to ', $distance->getValue(), ' ', Distance::METRES, PHP_EOL;


// With a read-write spy, we should be able to modify properties of the Distance object
try {
    $spy->value = 1852;
    echo 'Spy has set Distance value to ', $spy->value, PHP_EOL;
} catch (Exception $e) {
    echo 'EXCEPTION: ', $e->getMessage(), PHP_EOL;
}

echo 'Distance is set to ', $distance->getValue(Distance::NAUTICAL_MILES), ' ', Distance::NAUTICAL_MILES, PHP_EOL;
