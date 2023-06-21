# PasswordValidator
Verification of the characters used in the password. It is an extension of available validators for <b>Laminas Framework</b> (formerly <b>Zend Framework</b>).

he required length of the password itself can be determined by setting the <b>min</b> and <b>max</b> values in the options. The default values are:

 - <b> min</b> - 8 characters
 - <b>max</b> - 64 characters

The default settings of the validator allow the use of a password that has one lowercase and uppercase letter, a number and one of the special characters defined by the option settings for the <b>specialSings</b> key.Allowed characters are entered as a string.

In the option settings, we can specify what characters are allowed in a given password, we do it by setting the <b>type</b> value, it takes the following values (default setting is 1):

  - for setting the value to 1 - The password must have one lowercase and uppercase letter, a number and one of the special characters %specialSings%
  - for setting the value 2 - The password must have one lowercase and uppercase letter, a number and it is allowed to use one of the special characters %specialSings%
  - for setting the value 3 - the password must have one lowercase and one uppercase letter, a number, special characters are not allowed

```php

<?php

declare(strict_types = 1);
use Application\Validator\PasswordValidator;

include __DIR__ . '/vendor/autoload.php';

$password = 'aA11@45678#';

$validator = new PasswordValidator([
    'specialSings' => '!@#$%^*?\-_&'
]);

/**
 * Password aA12@45678# return true
 * Password aA11@45678# return false
 * Password aA12+45678# return false
 * Password aA189AsdE return false
 */

if ($validator->isValid($password) !== false) {
    echo 'Podane Hasło jest prawidłowe';
} else {
    echo 'Podane Hasło jest nie prawidłowe';
    $messages = $validator->getMessages();
    foreach ($messages as $key => $value) {
        echo $key . ' => ' . $value . '<br/>';
    }
}

$validator = new PasswordValidator([
    'type' => 2,
    'specialSings' => '!@#$%^*?\-_&'
]);

/**
 * Password aA12@45678# return true
 * Password aA11@45678# return false
 * Password aA12+45678# return false
 * Password aA189AsdE return true
 */

if ($validator->isValid($password) !== false) {
    echo 'Podane Hasło jest prawidłowe';
} else {
    echo 'Podane Hasło jest nie prawidłowe';
    $messages = $validator->getMessages();
    foreach ($messages as $key => $value) {
        echo $key . ' => ' . $value . '<br/>';
    }
}

$validator = new PasswordValidator([
    'type' => 3
]);

/**
 * Password aA12@45678# return false
 * Password aA11@45678# return false
 * Password aA12+45678# return false
 * Password aA189AsdE return true
 * Password aA189AsEE return false
 */

if ($validator->isValid($password) !== false) {
    echo 'Podane Hasło jest prawidłowe';
} else {
    echo 'Podane Hasło jest nie prawidłowe';
    $messages = $validator->getMessages();
    foreach ($messages as $key => $value) {
        echo $key . ' => ' . $value . '<br/>';
    }
}
```

Values for the validator translation keys for the Polish language version
 - passwordNotScalar - Hasło musi być wartością skalarną
 - passwordMin - Minimalna długość hasła to %min% znaków
 - passwordMax - Maksymalna długość hasła to %max% znaków
 - passwordLengthIdentical - Liczba minimalnej i maksymalnej liczby znaków nie może być identyczna
 - passwordMinMax - Liczba minimalnej liczby znaków nie może być większa od maksymalnej liczby znaków
 - passwordSpecialSingsNoDefined - Nie zdefiniowano dozwolonych znaków specjalnych
 - passwordEachSing - W haśle znaki o tej samej wartości nie mogą znajdować się obok siebie
 - passwordCharset - Hasło musi posiadać jedną małą i dużą literę, cyfrę oraz jeden ze znaków specjalnych %specialSings%
 - passwordCharset2 - Hasło musi posiadać jedną małą i dużą literę, cyfrę oraz dozwolone jest użycie jednego ze znaków specjalnych %specialSings%
 - passwordCharset3 - Haslo musi posiadać jedną małą i dużą literę, cyfrę nie dozwolone jest użycie znaków specjalnych
