<?php

$translations = require __DIR__ . '/../../../../vendor/orchestra/testbench-core/laravel/resources/lang/en/validation.php';

return array_merge($translations, [

    'auth_password' => 'The :attribute is not valid',

]);
