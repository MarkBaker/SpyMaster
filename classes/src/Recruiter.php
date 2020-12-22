<?php

namespace SpyMaster;

class Recruiter
{
    private function recruitNewInstance()
    {
        return function (string $className, ...$constructorArguments) {
            return new $className(...$constructorArguments);
        };
    }

    public function recruit(string $className, ...$constructorArguments)
    {
        return $this->recruitNewInstance()
            ->bindTo(null, $className)($className, ...$constructorArguments);
    }
}
