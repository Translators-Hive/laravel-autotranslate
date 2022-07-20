# Translators Hive Laravel Autotranslate

Translators Hive Laravel Autotranslate is a small tool for Laravel that gives you the ability to extract and auto translate untranslated string from your project files with one command.

## Support

admin@translators-hive.com

## Installation

You can install the package via composer:

```bash
composer require translators-hive/laravel-autotranslate
```

This package makes use of [Laravels package auto-discovery mechanism](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518), which means if you don't install dev dependencies in production, it also won't be loaded.

You can publish the config file with:
```bash
php artisan vendor:publish --provider="TranslatorsHive\LaravelAutoTranslate\ServiceProvider" --tag="config"
```

## Usage

Configure translators-hive.com api credentials in your .env file
```dotenv
    TranslatorsHiveEmail='your email here'
    TranslatorsHivePassword='your password here'
```

To collect, extract and auto translate all the strings you need to run:

``` bash
php artisan th:translate es,bg,de,fr
```

This command will create (if don't exist) `es.json`, `bg.json`, `de.json` and `fr.json` files inside the `resources/lang` directory.
If you have short keys enabled and used in your files (e.g. `auth.failed`) the command will create folders `es`, `bg`, `de` and `fr` inside `resources/lang` directory and PHP files inside by the short key's prefix (e.g. `auth.failed`).

You can also run the artisan command without the country code arguments.

``` bash
php artisan th:translate
```

In this case translation strings will be generated for the language specified in `app.locale` config.

> Note: Strings you have already translated will not be overwritten.

### Key Sorting

By default, the strings generated inside those JSON files will be sorted alphabetically by their keys.
If you want to turn off this feature just set `sort => false` in the config file.

### Searching

The way the strings are being collected and extracted is simple.

Searching is inside the directories defined in `search.dirs` config, using patterns defined in `search.patterns`, and finally is looked to collect strings
 which are the first argument of the functions defined in `search.functions`.

You can change any of these values inside the config file to suit you own needs.

### Translating



### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
