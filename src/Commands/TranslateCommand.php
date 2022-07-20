<?php

namespace TranslatorsHive\LaravelAutoTranslate\Commands;

use TranslatorsHive\LaravelAutoTranslate\Collections\DefaultKeyCollection;
use TranslatorsHive\LaravelAutoTranslate\Services\Translator;
use TranslatorsHive\LaravelAutoTranslate\Services\Parser;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Lang;

class TranslateCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'th:translate {lang?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate locale files with translated strings found in scanned files.';

    /**
     * Execute the translate command.
     *
     * @param Translator $translator
     * @param Parser $parser
     * @return int
     */
    public function handle(Translator $translator, Parser $parser): int
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $locales = $this->getLocales();
        $progressBar = $this->output->createProgressBar(count($locales));

        $this->info('Collecting strings for: '.implode(', ', $locales));

        $parser->parseKeys();

        $progressBar->setFormat('%current%/%max% [%bar%] %percent:3s%% %message%');
        $progressBar->setMessage('Collecting strings...');
        $progressBar->start();

        foreach ($locales as $locale) {
            $totalChars = 0;
            $totalStrings = 0;
            $strings = [];

            $collection = new DefaultKeyCollection;

            $progressBar->setMessage("Collecting strings for ".$locale."...");
            $stringsCollections = [];
            foreach ($this->getTypes() as $type) {
                $stringsCollections[] = $translator->collect($parser->getKeys($locale, $type), $type, $locale);
            }

            foreach($stringsCollections as $stringsCollection) {
                foreach($stringsCollection->all() as $key => $value) {
                    if ($value == "") {
                        $originValue = __($key, [], app()->getLocale());
                        if($originValue != "") {
                            $value = $originValue;
                        }
                        else {
                            $value = $key;
                        }
                        $strings[$key] = $value;
                        $totalChars += mb_strlen($key);
                        $totalStrings++;
                    }
                    else {
                        $collection->put($key,__($key,[],$locale));
                    }
                }
            }

            $stringsCollections = null;
            if(app()->getLocale() == $locale) {
                $translator->saveTranslations($collection, 'json', $locale);
            }
            else {
                if ($this->ask("\n".'Total phrases:'.$totalStrings.'. Total Characters: '.$totalChars.' Do you want to translate them? (yes/no)','yes')) {
                    $translationsProgressBar = $this->output->createProgressBar($totalStrings);
                    $translationsProgressBar->setMessage("Translating strings for ".$locale."...");
                    $translationsProgressBar->setFormat('%current%/%max% [%bar%] %percent:3s%% %message%');
                    $translationsProgressBar->setMessage('Translating strings... This is the best time to have your cup of coffee');
                    $translationsProgressBar->start();
                    foreach($strings as $string => $val) {
                        $collection = $translator->translate($collection, $string, $locale);
                        $translationsProgressBar->advance();
                    }
                    $translator->saveTranslations($collection, 'json', $locale);
                    $translationsProgressBar->finish();
                }
            }
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info(
            "\nTranslatable strings have been extracted and transferred for locale(s): ".implode(', ', $locales)
        );

        return 0;
    }

    /**
     * @return array
     */
    protected function getLocales(): array
    {
        return $this->argument('lang')
            ? explode(',', $this->argument('lang'))
            : [config('app.locale')];
    }

    /**
     * @return array
     */
    protected function getTypes(): array
    {
        return array_keys(array_filter(config('translators-hive-autotranslate.localize')));
    }
}
