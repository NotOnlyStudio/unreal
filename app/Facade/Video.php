<?php

namespace App\Facade;

use App\Service\Video\VideoServices;
use Illuminate\Support\Facades\Facade;

/**
 * @method VideoServices static videoID(string $string)
 */
class Video extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'video';
    }
}
