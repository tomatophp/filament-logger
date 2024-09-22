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
