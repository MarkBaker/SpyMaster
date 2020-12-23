<?php

namespace SpyMaster;

use SpyMaster\Exception as Exception;

class Manipulator
{
    private function recruitNewInstance()
    {
        return function (string $className, ...$constructorArguments) {
            return new $className(...$constructorArguments);
        };
    }

    public function recruit(string $className, ...$constructorArguments)
    {
        if (!class_exists($className)) {
            throw new Exception(sprintf('The first argument passed to recruit must be a valid class name'));
        } elseif ((new \ReflectionClass($className))->isInternal()) {
            throw new Exception(sprintf('SpyMaster is unable to access PHP internal classes like %s', $className));
        }

        return $this->recruitNewInstance()
            ->bindTo(null, $className)($className, ...$constructorArguments);
    }

    private function executor() {
        return function($instance, string $methodName, ...$methodArguments) {
            return $instance->$methodName(...$methodArguments);
        };
    }

    public function execute($instance, string $methodName, ...$methodArguments) {
        if (!is_object($instance)) {
            throw new Exception(sprintf('The first argument passed to execute must be an object'));
        } elseif ((new \ReflectionClass(get_class($instance)))->isInternal()) {
            throw new Exception(sprintf('SpyMaster is unable to access PHP internal classes like %s', get_class($instance)));
        }

        return $this->executor()
            ->bindTo(null, get_class($instance))($instance, $methodName, ...$methodArguments);
    }
}
