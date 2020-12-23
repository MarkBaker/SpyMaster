<?php

namespace testing;

class testClassForExecutor
{
    public $value = 1;

    private function add(int $value = 1)
    {
        $this->value += $value;
        return 0 - $this->value;
    }
}
