<?php

namespace TranslatorsHive\LaravelAutoTranslate\Contracts;

use Illuminate\Support\Collection;

interface Collectable
{
    /**
     * @param string $locale
     * @return Collection
     */
    public function getTranslated(string $locale): Collection;
}
