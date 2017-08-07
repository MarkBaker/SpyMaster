<?php

include('../classes/Bootstrap.php');

use SpyMaster\SpyMaster as SpyMaster;



// You can't use SpyMaster to access PHP built-in classes
$dto = new DateTime();

// So we should get an exception thrown when we try to spy on a DateTime object
try {
    $spy = (new SpyMaster($dto))->infiltrate();
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

var_dump($spy->listProperties());
