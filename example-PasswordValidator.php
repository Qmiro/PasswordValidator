<?php

/**
 * @filesource example-PasswordValidator.php
 * 
 * Message templates for Polish language
 * passwordNotScalar - Hasło musi być wartością skalarną
 * passwordMin - Minimalna długość hasła to %min% znaków
 * passwordMax - Maksymalna długość hasła to %max% znaków
 * passwordLengthIdentical - Liczba minimalnej i maksymalnej liczby znaków nie może być identyczna
 * passwordMinMax - Liczba minimalnej liczby znaków nie może być większa od maksymalnej liczby znaków
 * passwordSpecialSingsNoDefined - Nie zdefiniowano dozwolonych znaków specjalnych
 * passwordEachSing - W haśle znaki o tej samej wartości nie mogą znajdować się obok siebie
 * passwordCharset - Hasło musi posiadać jedną małą i dużą literę, cyfrę oraz jeden ze znaków specjalnych %specialSings%
 * passwordCharset2 - Hasło musi posiadać jedną małą i dużą literę, cyfrę oraz dozwolone jest użycie jednego ze znaków specjalnych %specialSings%
 * passwordCharset3 - Haslo musi posiadać jedną małą i dużą literę, cyfrę nie dozwolone jest użycie znaków specjalnych
 */
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