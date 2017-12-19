# Comilio PHP SMS Send

PHP client library to send SMS messages using Comilio SMS Gateway.

To use this library, you must have a valid account on https://www.comilio.it.

**Please note** SMS messages sent with this library will be deducted by your Comilio account credits.

For any questions, please contact us at tech@comilio.it

# How to send a message
 
```php
$my_sms = new Comilio\SmsMessage();
$my_sms->authenticate('your_username', 'your_password')
       ->setRecipients('+393400000000')
       ->send('Hello World!');
```


# Installation

## Composer (recommended)

Install it via composer (https://getcomposer.org/).

* Run `composer require comilio/sms-send`
* See script example https://github.com/comilio/php-sms-send/blob/master/examples/send_sms.php


## Other autoloaders

This package is PSR-4 compliant, so you can clone the repository in your project and a use PSR-4 compatible autoloader (e.g. Symfony or Laravel)

## Manual installation

You can simply clone the repository into your project and use the classes contained in src/ directory.

Please check the examples directory here: https://github.com/comilio/php-sms-send/blob/master/examples/

# More info

You can check out our website https://www.comilio.it or contact us.

# Contributing

If you wish to contribute to this project, please feel free to send us pull request. We'll be happy to check them out!
