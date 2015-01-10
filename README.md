# smsaero
A simple smsaero.ru API provider for laravel 4

## Requirements
- Laravel 4

## Installation
In the `require` key of `composer.json` file add the following

    "klucherev/smsaero": "1.0-dev"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'Klucherev\Smsaero\SmsaeroServiceProvider'` to the end of the `$providers` array

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Klucherev\Smsaero\SmsaeroServiceProvider',

),
```
### Configuration

Run 

    $ php artisan config:publish klucherev/smsaero

This will generate config file in /app/config/packages/klucherev/smsaero that you will need to edit.

## Code Examples

```php
// get balance
$balance = Smsaero::getBalance();

// send SMS to
$sms = Smsaero::send($to, $text, $from, $date);

```

## License

[MIT License](http://opensource.org/licenses/MIT).

Copyright 2015 [Klucherev Alexey](http://bizapp.ru/)