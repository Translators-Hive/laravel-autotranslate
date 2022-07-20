<?php

namespace TranslatorsHive\LaravelAutoTranslate\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

class Translator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'translator';
    }
}
