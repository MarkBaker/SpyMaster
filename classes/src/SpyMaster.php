<?php

namespace SpyMaster;

use SpyMaster\Exception as Exception;

class SpyMaster
{
    const SPY_READ_ONLY = 'read-only';

    const SPY_READ_WRITE = 'read-write';

    private static $spyTypes = [
        self::SPY_READ_ONLY,
        self::SPY_READ_WRITE,
    ];
    
    private $targetObject;

    public function __construct($targetObject = null) {
        if (is_null($targetObject)) {
            throw new Exception('You must specify the object that you want to spy on');
        } elseif (!is_object($targetObject)) {
            throw new Exception('Argument must be an object');
        } elseif ((new \ReflectionClass(get_class($targetObject)))->isInternal()) {
            throw new Exception(sprintf('SpyMaster is unable to access PHP internal classes like %s', get_class($targetObject)));
        }

        $this->targetObject = $targetObject;
    }

    protected function getHandlerReadOnly() {
        return function() {
            $properties = [];
            foreach (array_keys(get_object_vars($this)) as $propertyName) {
                $properties[$propertyName] = &$this->$propertyName;
            }

            return new class ($properties) extends Spy {
                use Traits\ReadOnly;
            };
        };
    }

    protected function getHandlerReadWrite() {
        return function() {
            $properties = [];
            foreach (array_keys(get_object_vars($this)) as $propertyName) {
                $properties[$propertyName] = &$this->$propertyName;
            }

            return new class ($properties) extends Spy {
                use Traits\ReadWrite;
            };
        };
    }

    private static function adjustTypeName($value) {
        return str_replace('-', '', ucwords(strtolower($value), " -_"));
    }

    protected function getHandler($type) {
        $handlerName = __FUNCTION__ . self::adjustTypeName($type);
        if (!method_exists($this, $handlerName)) {
            throw new Exception(sprintf('SpyMaster does not support %s spies', $type));
        }

        return $this->$handlerName();
    }

    public function infiltrate($type = self::SPY_READ_ONLY) {
        $anonymous = $this->getHandler($type);

        $spy = $anonymous->bindTo($this->targetObject, get_class($this->targetObject));
        if ($spy === false) {
            throw new Exception(sprintf('SpyMaster is unable to bind Spy to instance of %s', get_class($this->targetObject)));
        }
        return $spy();
    }
}
