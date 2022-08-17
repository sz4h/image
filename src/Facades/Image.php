<?php

namespace Space\Image\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Space\Image\Image
 */
class Image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Space\Image\Image::class;
    }
}
