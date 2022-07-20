<?php

namespace TranslatorsHive\LaravelAutoTranslate\Services;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class FileFinder
{
    /**
     * @var array
     */
    private $config;

    /**
     * FileFinder constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('translators-hive-autotranslate');
    }

    /**
     * @return Collection
     */
    public function getFiles(): Collection
    {
        $directories = array_map(static function ($dir) {
            return base_path($dir);
        }, $this->config['search']['dirs']);

        return new Collection(
            (new Finder)->in($directories)->name($this->config['search']['patterns'])->files()
        );
    }
}
