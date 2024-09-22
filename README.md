![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-logger/master/arts/3x1io-tomato-logger.jpg)

# Filament logger

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-logger/version.svg)](https://packagist.org/packages/tomatophp/filament-logger)
[![License](https://poser.pugx.org/tomatophp/filament-logger/license.svg)](https://packagist.org/packages/tomatophp/filament-logger)
[![Downloads](https://poser.pugx.org/tomatophp/filament-logger/d/total.svg)](https://packagist.org/packages/tomatophp/filament-logger)

Log all user activity to file or log driver and preview it on your FilamentPHP panel

## Screenshots

![Logger](https://raw.githubusercontent.com/tomatophp/filament-logger/master/arts/logger.png)
![View Log](https://raw.githubusercontent.com/tomatophp/filament-logger/master/arts/view-log.png)
![Log File](https://raw.githubusercontent.com/tomatophp/filament-logger/master/arts/log-file.png)

## Installation

```bash
composer require tomatophp/filament-logger
```
after install your package please run this command

```bash
php artisan filament-logger:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentLogger\FilamentLoggerPlugin::make())
```


after install please publish the config file by using this command

```bash
php artisan vendor:publish --tag="filament-logger-config"
```

on your `filament-logger.php` config file please add the guard of user or middleware you went to track

```php
    'guards' => [
        'web',
        'auth:accounts'
    ],
```

to track your panel 

```php
    'guards' => [
        'web',
        'panel:admin'
    ],
```

where `admin` is the id of the panel.

## Using

you can add a custom log from anywhere in your code by using this code

```php
use TomatoPHP\FilamentLogger\Facades\FilamentLogger;

FilamentLogger::log(message:'Your message here', level:'info');
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-logger-config"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-logger-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-logger-migrations"
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
