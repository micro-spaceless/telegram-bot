# Telegram Bot Package for Laravel 7.x

[![Total Downloads](https://img.shields.io/packagist/dt/micro-spaceless/telegram-bot)](https://img.shields.io/packagist/dt/micro-spaceless/telegram-bot)
[![Downloads Month](https://img.shields.io/packagist/dm/micro-spaceless/telegram-bot.svg)](https://img.shields.io/packagist/dm/micro-spaceless/telegram-bot)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D7.3-8892BF.svg)](https://php.net/)


## Table of Contents
- [Installation](#installation)
- [Usage](#Usage)
- [Security](#security)

## Installation

Install this package through [Composer](https://getcomposer.org/).

Edit your project's `composer.json` file to require `micro-spaceless/telegram-bot`

Create *composer.json* file:
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "micro-spaceless/telegram-bot": "^1.0"
    }
}
```
And run composer update

**Or** run a command in your command line:

```bash
composer require micro-spaceless/telegram-bot
```

Copy the package config and migrations to your project with the publish command:

```bash
php artisan vendor:publish --provider="MicroSpaceless\TelegramBot\Providers\BaseServiceProvider"
```

In Laravel find the `providers` key in your `config/app.php` and register the Base Service Provider.

```php
'providers' => [
    // ...
    'MicroSpaceless\TelegramBot\Providers\BaseServiceProvider',
]
```

Find the `aliases` key in your `config/app.php` and add the Telegram facade alias.

```php
'aliases' => [
    // ...
    'Telegram' => 'MicroSpaceless\TelegramBot\Telegram',
]
```

## Usage

```php
$telegram = new Telegram();
$telegram->sendMessage('Hi!');
```

## Security

If you discover any security related issues, please email ed.arm.2000@gmail.com instead of using the issue tracker.
