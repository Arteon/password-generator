# Password Generator

This password generator will generate random passwords which will comply the given
password policy.

## Usage

Require this project using composer. This will make it available for your project.

```
composer require hosteurope/password-generator
```

Don't forget to require the composer autoloader, if not already done: 

```php
require 'vendor/autoload.php';
```

Then, to use the password generator, just create a new instance and set the requirements.

In this example, we require the password to be 16 characters in length, including
at least four lowercase characters, one uppercase character, two numbers and one symbol.

```php
$generator = new \PasswordGenerator\Generator;
$generator->requireLowercase(4)
    ->requireUppercase(1)
    ->requireNumber(2)
    ->requireSymbol(1)
    ->length(16);

var_dump($generator->get()); // string(16) "ZE0ff_Rgb6uGq28w"

var_dump($generator->get()); // string(16) "r5zRLn+8o#(gGffa"
```

## Custom Character Types

If you're not satisfied with the given character types, feel free to add your own class.

Requirements are to extend the `PasswordGenerator\Characters\GenericCharacter` class. Then,
add a new instance of that object to the desired Generator using

```php
$generator->addCharacterType('myCharacterType', $customCharacterType);
```

You then can require and use them just as before:

```php
$generator->requireMyCharacterType(1);
```

## License

This library is published under the MIT license. See `LICENSE` for more information.

Copyright (c) 2016 Host Europe Group Ltd.
