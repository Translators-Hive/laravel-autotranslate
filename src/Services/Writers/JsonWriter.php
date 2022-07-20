<?php

namespace TranslatorsHive\LaravelAutoTranslate\Services\Writers;

use TranslatorsHive\LaravelAutoTranslate\Contracts\Translatable;
use TranslatorsHive\LaravelAutoTranslate\Contracts\Writable;
use Illuminate\Filesystem\Filesystem;

class JsonWriter implements Writable
{
    /**
     * @param string $locale
     * @param Translatable $keys
     */
    public function put(string $locale, Translatable $keys): void
    {
        $file = lang_path($locale.".json");

        (new Filesystem)->put(
            $file,
            $keys->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
