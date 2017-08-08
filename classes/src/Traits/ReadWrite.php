<?php

namespace SpyMaster\Traits;

use SpyMaster\Exception as Exception;

trait ReadWrite
{
    public function __set($propertyName, $propertyValue)
    {
        if (array_key_exists($propertyName, $this->targetProperties)) {
            return $this->targetProperties[$propertyName] = $propertyValue;
        }

        throw new Exception("Property {$propertyName} does not exist");
    }
}
