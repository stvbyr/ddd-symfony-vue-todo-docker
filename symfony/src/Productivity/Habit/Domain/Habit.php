<?php

namespace Productivity\Habit\Domain;

class Habit
{
    public function __construct(Type $var = null)
    {
        $this->var = $var;
    }
}
