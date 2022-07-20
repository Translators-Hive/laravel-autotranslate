<?php

namespace TranslatorsHive\LaravelAutoTranslate;

use TranslatorsHive\LaravelAutoTranslate\Commands\TranslateCommand;
use TranslatorsHive\LaravelAutoTranslate\Services\Collectors\DefaultKeyCollector;
use TranslatorsHive\LaravelAutoTranslate\Services\Collectors\JsonKeyCollector;
use TranslatorsHive\LaravelAutoTranslate\Services\Translator;
use TranslatorsHive\LaravelAutoTranslate\Services\Writers\DefaultWriter;
use TranslatorsHive\LaravelAutoTranslate\Services\Writers\JsonWriter;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/translators-hive-autotranslate.php' => config_path('translators-hive-autotranslate.php'),
            ], 'config');
			
            $this->commands(TranslateCommand::class);
        }
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/translators-hive-autotranslate.php', 'translators-hive-autotranslate');

        $this->registerContainerClasses();
    }

    /**
     * @return void
     */
    private function registerContainerClasses(): void
    {
        $this->app->singleton('translator', Translator::class);

        $this->app->bind('translator.writers.default', DefaultWriter::class);
        $this->app->bind('translator.writers.json', JsonWriter::class);

        $this->app->bind('translator.collector.default', DefaultKeyCollector::class);
        $this->app->bind('translator.collector.json', JsonKeyCollector::class);
    }
}