<?php

include('../classes/Bootstrap.php');

include('./sampleClasses/Distance.php');

use SpyMaster\SpyMaster as SpyMaster;

// Instantiate Distance object with constructor arguments of 10 miles
// This will be converted to metres internally
$distance = new Distance(10, Distance::MILES);

// Create our spy object
$spy = (new SpyMaster($distance))->infiltrate();


// Use the spy to read the Distance value property
echo 'Spy reads Distance value as ', $spy->value, PHP_EOL;

// Get the Distance value property using it's getter method for comparison
echo 'Distance is set to ', $distance->getValue(), ' ', Distance::METRES, PHP_EOL;


// Spy should throw an Exception if we try to access a property that doesn't exist in the Distance object
try {
    var_dump($spy->gazebo);
} catch (Exception $e) {
    echo 'EXCEPTION: ', $e->getMessage(), PHP_EOL;
}


// Our default spy is read-only, so we shouldn't be able to modify properties of the Distance object
try {
    $spy->value = 1852;
    echo 'Spy has set Distance value to ', $spy->value, PHP_EOL;
} catch (Exception $e) {
    echo 'EXCEPTION: ', $e->getMessage(), PHP_EOL;
}

echo 'Distance is set to ', $distance->getValue(Distance::NAUTICAL_MILES), ' ', Distance::NAUTICAL_MILES, PHP_EOL;
