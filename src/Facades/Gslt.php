<?php

namespace ConnySjoblom\Gslt\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ConnySjoblom\Gslt\Gslt
 */
class Gslt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gslt';
    }
}
