<?php

namespace TranslatorsHive\LaravelAutoTranslate\Collections;

use TranslatorsHive\LaravelAutoTranslate\Contracts\Translatable;
use Illuminate\Support\Collection;

class JsonKeyCollection extends Translatable
{
    public function sortAlphabetically(): Collection
    {
        return $this->sortKeys(SORT_NATURAL | SORT_FLAG_CASE);
    }
}
