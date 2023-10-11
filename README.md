# Cony
Cony is a PHP library for convert integer into alphabet, and reversely too

## Installation
```bash
composer require carry0987/cony
```
> **Note** You can install [`bcmath`](https://secure.php.net/manual/en/book.bc.php) extension in order to get better performance

## Usage
```php
require dirname(__DIR__).'/vendor/autoload.php';

use carry0987\Cony;

echo Cony::toNumeric('test'); // 4544743
echo '<br />';
echo Cony::toAlphanumeric(4544743); // test
```

If you want the alphaID to be at least 3 letter long, use the `$padUp` argument.
> In most cases this is better than totally random ID generators because this can easily avoid duplicate ID's.
>
> For example if you correlate the alpha ID to an auto incrementing ID in your database, you're done.
```php
Cony::toAlphanumeric(4540899, 3); // test
Cony::toNumeric('test', 3); // 4540899
```

Although this function's purpose is to just make the ID short - and not so much secure, with third argument `secureKey` you can optionally supply a password to make it harder to calculate the corresponding numeric ID.
```php
Cony::toAlphanumeric(11282993, 3, 'heuh2ui12'); // test
Cony::toNumeric('test', 3, 'heuh2ui12'); // 11282993
```

And, for final, you can easy transform alphanumeric result:
```php
Cony::toAlphanumeric(21663528, 0, null, Cony::TRANSFORM_UPPERCASE); // B2TFK
Cony::toAlphanumeric(21663528, 0, null, Cony::TRANSFORM_LOWERCASE); // b2tfk
```
