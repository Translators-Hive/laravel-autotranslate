<?php

namespace TranslatorsHive\LaravelAutoTranslate\Services\Collectors;

use TranslatorsHive\LaravelAutoTranslate\Collections\JsonKeyCollection;
use TranslatorsHive\LaravelAutoTranslate\Contracts\Collectable;
use Illuminate\Support\Collection;

class JsonKeyCollector implements Collectable
{
    /**
     * @param string $locale
     * @return Collection
     */
    public function getTranslated(string $locale): Collection
    {
        $file = lang_path($locale.".json");

        if (! file_exists($file)) {
            return new JsonKeyCollection;
        }

        return new JsonKeyCollection(
            json_decode(file_get_contents($file), true)
        );
    }
}
