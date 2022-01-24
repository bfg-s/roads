<?php

namespace Lar\Roads;

use Illuminate\Support\Facades\Facade as FacadeIlluminate;

/**
 * Class Facade.
 *
 * @package Lar
 */
class Facade extends FacadeIlluminate
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Roads::class;
    }
}
