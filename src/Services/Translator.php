<?php

namespace TranslatorsHive\LaravelAutoTranslate\Services;

use TranslatorsHive\LaravelAutoTranslate\Contracts\Collectable;
use TranslatorsHive\LaravelAutoTranslate\Contracts\Translatable;
use TranslatorsHive\LaravelAutoTranslate\Contracts\Writable;

class Translator
{
    protected $translatorsHiveApi = "https://translators-hive.com/api";
    protected $token;

    /**
     * @param Translatable $collection
     * @param string $string
     * @param string $type
     * @param string $locale
     */
    public function translate(Translatable $collection, string $string, string $type, string $locale)
    {
        $appLocale = app()->getLocale();
        if($locale != $appLocale) {
            if(!$this->token) {
                $this->translatorsHiveAuth();
            }
            $localeString = __($string, [], $appLocale);
            if($localeString && $string!=$localeString) {
                $collection->put($string,$this->getTranslation($localeString,$locale,$appLocale));
            }
            else {
                $collection->put($string,$this->getTranslation($string,$locale,$appLocale));
            }
        }
        else {
            $collection->put($string,$string);
        }
        return $collection;
    }

    /**
     * @param Translatable $collection
     * @param string $string
     * @param string $type
     * @param string $locale
     */
    public function saveTranslations(Translatable $collection, string $type, string $locale)
    {
        $this->getWriter($type)->put($locale, $collection);
    }

    /**
     * @param Translatable $keys
     * @param string $type
     * @param string $locale
     * @return Translatable
     */
    public function collect(Translatable $keys, string $type, string $locale): Translatable
    {
        return $keys
            ->merge($this->getCollector($type)->getTranslated($locale))
            ->when(config('translators-hive-autotranslate.sort'), function (Translatable $keyCollection) {
                return $keyCollection->sortAlphabetically();
            });
    }

    /**
     * @param string $type
     * @return Writable
     */
    protected function getWriter(string $type): Writable
    {
        return app("translator.writers.$type");
    }

    /**
     * @param string $type
     * @return Collectable
     */
    protected function getCollector(string $type): Collectable
    {
        return app("translator.collector.$type");
    }

    public function translatorsHiveAuth() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->translatorsHiveApi."/login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            [
                "email"=>config('translators-hive-autotranslate.translators-hive-auth.email'),
                "password"=>config('translators-hive-autotranslate.translators-hive-auth.password')
            ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $resJson = json_decode($result);
        if($resJson->success == true) {
            $this->token = $resJson->data->token;
        }
        else {
            if(is_object($resJson)) {
                echo $resJson->data->error."\n"."https://translators-hive.com\n\n";
                exit;
            }
        }
        curl_close ($ch);
    }

    public function getTranslation($text,$locale,$appLocale) {

        if($text == "") {
            return $text;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->translatorsHiveApi."/translate");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer ".$this->token]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            [
                "src_lang"=>$appLocale,
                "dest_lang"=>$locale,
                "text"=>$text
            ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        if($info['http_code'] == 504 || $info['http_code'] == 503) {
            return $this->getTranslation($text,$locale,$appLocale);
        }
        else if($info['http_code'] != 200) {
            curl_close($ch);
            echo "\n".$info['http_code']."\n";
            echo "Something went wrong... Please wait a few minutes and try again."."\n"."https://translators-hive.com\n\n";
            exit;
        }
        $resJson = json_decode($result);
        if($resJson->success !== true) {
            if(is_object($resJson)) {
                echo $resJson->data->error."\n"."https://translators-hive.com\n\n";
                exit;
            }
        }
        curl_close ($ch);
        return $resJson->data->text;
    }
}
