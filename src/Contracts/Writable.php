<?php

namespace TranslatorsHive\LaravelAutoTranslate\Contracts;

interface Writable
{
    /**
     * @param string $locale
     * @param Translatable $keys
     */
    public function put(string $locale, Translatable $keys): void;
}
