<?php

namespace SpyMaster\Traits;

use SpyMaster\Exception as Exception;

trait ReadOnly
{
    public function __set($propertyName, $propertyValue)
    {
        if (!array_key_exists($propertyName, $this->targetProperties)) {
            throw new Exception("Property {$propertyName} does not exist");
        }

        throw new Exception("ReadOnly Spies are not permitted to change property values");
    }
}
