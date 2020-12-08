# Laravel auth password

[![Build Status](https://travis-ci.com/mjelamanov/laravel-auth-password.svg?branch=master)](https://travis-ci.com/mjelamanov/laravel-auth-password)
[![StyleCI](https://github.styleci.io/repos/211689065/shield?branch=master)](https://github.styleci.io/repos/211689065)
[![Latest Stable Version](https://poser.pugx.org/mjelamanov/laravel-auth-password/version)](https://packagist.org/packages/mjelamanov/laravel-auth-password)
[![License](https://poser.pugx.org/mjelamanov/laravel-auth-password/license)](https://packagist.org/packages/mjelamanov/laravel-auth-password)

This package allows you to validate an authenticated user's password. For laravel 6.0 and above please use the native [password](https://laravel.com/docs/6.x/validation#rule-password) rule.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Use rule](#use-rule)
- [Use extension](#use-extension)

## Requirements

- PHP 7.1 or above.
- Laravel 5.8 or 6.0.

## Installation

```bash
composer require mjelamanov/laravel-auth-password
```

Don't forget to add the translation key bellow to your app's lang files.

```php
// in resources/lang/en/validation.php

'auth_password' => 'The :attribute is not valid',
```

## Use rule

Create a rule instance via [RuleFactoryInterface](https://github.com/mjelamanov/laravel-auth-password/blob/master/src/Rule/RuleFactoryInterface.php).

```php
// In your controller

use Illuminate\Http\Request;
use Mjelamanov\Laravel\AuthPassword\Rule\RuleFactoryInterface;

public function changeUserPassword(Request $request, RuleFactoryInterface $ruleFactory)
{
    $this->validate($request, [
        'current_password' => ['bail', 'required', 'min:6', $ruleFactory->createRule()],
        'new_password' => 'bail|required|string|min:6',
        'new_password_confirmation' => 'bail|required|confirmed',
    ]);

    // Passwords are valid. Place your logic here.
}
```

You can specify a different guard name.

```php
$ruleFactory->createRule(); // default application's guard
$ruleFactory->createRule('web');
$ruleFactory->createRule('api');

// or custom guard
$ruleFactory->createRule('admin');

$ruleFactory->createRule('non-existen'); // Throws \InvalidArgumentException 
```

## Use extension

You may use the validation extension also.

```php
// In your controller

public function changeUserPassword(Request $request)
{
    $this->validate($request, [
        'current_password' => 'bail|required|min:6|auth_password',
        'new_password' => 'bail|required|string|min:6',
        'new_password_confirmation' => 'bail|required|confirmed',
    ]);

    // Passwords are valid. Place your logic here.
}
```

You can also specify a different guard name.

```php
'field' => 'auth_password', // default application's guard
'field' => 'auth_password:web',
'field' => 'auth_password:api',

// or custom guard
'field' => 'auth_password:admin',

'field' => 'auth_password:non_existen', // Throws \InvalidArgumentException
```
