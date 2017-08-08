<?php

namespace SpyMaster;

use SpyMaster\Exception as Exception;

abstract class Spy
{
    protected $className;

    protected $targetProperties;

    public function __construct($className, &$properties)
    {
        $this->className = $className;
        foreach ($properties as $propertyName => &$propertyValue) {
            $this->targetProperties[$propertyName] = &$propertyValue;
        }
    }

    public function __get($propertyName)
    {
        if (array_key_exists($propertyName, $this->targetProperties)) {
            return $this->targetProperties[$propertyName];
        }

        throw new Exception("Property {$propertyName} does not exist");
    }

    public function __isset($propertyName)
    {
        return array_key_exists($propertyName, $this->targetProperties);
    }

    public function __unset($propertyName)
    {
        if (array_key_exists($propertyName, $this->targetProperties)) {
            throw new Exception('Spies are not permitted to unset properties');
        }

        throw new Exception("Property {$propertyName} does not exist");
    }

    public function listProperties()
    {
        return array_keys($this->targetProperties);
    }

    public function getClassName()
    {
        return $this->className;
    }
}
