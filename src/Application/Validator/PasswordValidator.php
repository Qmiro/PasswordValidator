<?php

/**
 * @name PasswordValidator
 * @package Validator
 * @version 1.3.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2023 (https://www.appsonline.eu)
 * @link https://www.appsonline.eu
 */
declare(strict_types = 1);
namespace Application\Validator;

use Laminas\Validator\AbstractValidator;
use Laminas\Stdlib\ArrayUtils;
use Traversable;

class PasswordValidator extends AbstractValidator
{

    /**
     *
     * @var array
     */
    protected $options = [
        'type' => 1,
        'min' => 8,
        'max' => 64,
        'specialSings' => null
    ];

    /**
     *
     * @var string
     */
    private const PASSWORD_NOT_SCALAR = 'passwordNotScalar';

    /**
     *
     * @var string
     */
    private const PASSWORD_MIN = 'passwordMin';

    /**
     *
     * @var string
     */
    private const PASSWORD_MAX = 'passwordMax';

    /**
     *
     * @var string
     */
    private const PASSWORD_LENGTH_IDENTICAL = 'passwordLengthIdentical';

    /**
     *
     * @var string
     */
    private const PASSWORD_MIN_MAX = 'passwordMinMax';

    /**
     *
     * @var string
     */
    private const PASSWORD_SPECIAL_SINGS_NO_DEFINED = 'passwordSpecialSingsNoDefined';

    /**
     *
     * @var string
     */
    private const PASSWORD_EACH_SINGS = 'passwordEachSings';

    /**
     *
     * @var string
     */
    private const PASSWORD_CHARSET = 'passwordCharset';

    /**
     *
     * @var string
     */
    private const PASSWORD_CHARSET_2 = 'passwordCharset2';

    /**
     *
     * @var string
     */
    private const PASSWORD_CHARSET_3 = 'passwordCharset3';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::PASSWORD_NOT_SCALAR => "The password must be a scalar value",
        self::PASSWORD_MIN => "The minimum password length is %min% characters",
        self::PASSWORD_MAX => "The maximum password length is %max% characters",
        self::PASSWORD_MIN_MAX => 'The minimum number of characters cannot exceed the maximum number of characters',
        self::PASSWORD_SPECIAL_SINGS_NO_DEFINED => 'Allowed special characters are not defined',
        self::PASSWORD_LENGTH_IDENTICAL => 'The number of minimum and maximum characters cannot be the same',
        self::PASSWORD_EACH_SINGS => 'Characters with the same value cannot be next to each other in a password',
        self::PASSWORD_CHARSET => "The password must contain one upper and lower case letter, one digit and one of the special characters %specialSings%",
        self::PASSWORD_CHARSET_2 => "The password must have one uppercase and lowercase letter, one digit, and one of the special characters is allowed %specialSings%",
        self::PASSWORD_CHARSET_3 => 'The password must have one lowercase and one uppercase letter, the number is not allowed to use special characters'
    ];

    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $messageVariables = [
        'min' => [
            'options' => 'min'
        ],
        'max' => [
            'options' => 'max'
        ],
        'specialSings' => [
            'options' => 'specialSings'
        ]
    ];

    /**
     *
     * @name constructor
     * @access public
     * @param array|Traversable $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        parent::__construct($options);
    }

    /**
     *
     * @name isValid
     * @access public
     * @param string $value
     * @see \Laminas\Validator\ValidatorInterface::isValid()
     * @return boolean
     */
    public function isValid($value)
    {
        if (! is_scalar($value)) {
            $this->error(self::PASSWORD_NOT_SCALAR);
            return false;
        }

        if (strlen($value) < $this->options['min']) {
            $this->error(self::PASSWORD_MIN);
            return false;
        }

        if (strlen($value) > $this->options['max']) {
            $this->error(self::PASSWORD_MAX);
            return false;
        }

        if ($this->options['min'] == $this->options['max']) {
            $this->error(self::PASSWORD_LENGTH_IDENTICAL);
            return false;
        }

        if ($this->options['max'] > 0 && $this->options['min'] > $this->options['max']) {
            $this->error(self::PASSWORD_MIN_MAX);
            return false;
        }

        $specialSings = '';
        if (! is_null($this->options['specialSings'])) {
            $specialSings = trim($this->options['specialSings']);
        }

        if (in_array($this->options['type'], [
            1,
            2
        ])) {

            if ($specialSings == '') {
                $this->error(self::PASSWORD_SPECIAL_SINGS_NO_DEFINED);
                return false;
            }
        }

        $length = '{' . $this->options['min'] . ',}';
        if ($this->options['max'] > 0) {
            $length = '{' . $this->options['min'] . ',' . $this->options['max'] . '}';
        }

        switch ($this->options['type']) {

            case 1:

                if (! preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[' . $specialSings . '])([a-zA-Z0-9' . $specialSings . ']' . $length . ')$/', $value)) {
                    $this->error(self::PASSWORD_CHARSET);
                    return false;
                }

                break;

            case 2:

                if (! preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9' . $specialSings . ']' . $length . ')$/', $value)) {
                    $this->error(self::PASSWORD_CHARSET_2);
                    return false;
                }

                break;

            case 3:

                if (! preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]' . $length . ')$/', $value)) {
                    $this->error(self::PASSWORD_CHARSET_3);
                    return false;
                }

                break;

            default:

                if (! preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[' . $specialSings . '])([a-zA-Z0-9' . $specialSings . ']' . $length . ')$/', $value)) {
                    $this->error(self::PASSWORD_CHARSET);
                    return false;
                }

                break;
        }

        $identical = [];
        $arr = str_split($value);
        $c = sizeof($arr);

        for ($v = 0; $v <= $c; $v ++) {
            if (isset($arr[($v + 1)])) {
                if ($arr[$v] == $arr[($v + 1)]) {
                    $identical[] = $v;
                }
            }
        }

        if (sizeof($identical)) {
            $this->error(self::PASSWORD_EACH_SINGS);
            return false;
        }

        return true;
    }
}
