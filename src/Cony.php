<?php
declare(strict_types=1);

namespace carry0987\Cony;

use carry0987\Cony\Exception\ConyException;

/*
 * Translate number to short alphanumeric
 */
class Cony
{
    const TRANSFORM_NONE = 0;
    const TRANSFORM_UPPERCASE = 1;
    const TRANSFORM_LOWERCASE = 2;

    /**
     * Translates a short alphanumeric string back to a numeric ID.
     *
     * @param string      $input     The alphanumeric string to convert.
     * @param int         $padUp     The amount to pad up, default is 0.
     * @param string|null $secureKey An optional key to secure the conversion.
     *
     * @return int The numeric representation of the input string.
     */
    public static function toNumeric(string $input, int $padUp = 0, string $secureKey = null): int
    {
        $dictionary = self::generateDictionary();
        $dictionaryLength = strlen($dictionary);
        if (!empty($secureKey)) {
            $dictionary = self::secureEncrypt($dictionary, $dictionaryLength, $secureKey);
        }

        return self::convertToNumber($input, $dictionary, $dictionaryLength, $padUp);
    }

    /**
     * Converts a numeric ID to a short alphanumeric string.
     *
     * @param int         $input         The numeric ID to convert.
     * @param int         $padUp         The amount to pad up, default is 0.
     * @param string|null $secureKey     An optional key to secure the conversion.
     * @param int         $transformType The transformation type (none, uppercase, lowercase).
     *
     * @return string The alphanumeric representation of the input number.
     */
    public static function toAlphanumeric(int $input, int $padUp = 0, string $secureKey = null, int $transformType = self::TRANSFORM_NONE): string
    {
        $dictionary = self::generateDictionary();
        $dictionaryLength = strlen($dictionary);
        if (!empty($secureKey)) {
            $dictionary = self::secureEncrypt($dictionary, $dictionaryLength, $secureKey);
        }

        return self::transformType(
            self::convertToAlphanumeric((int) $input, $dictionary, $dictionaryLength, $padUp),
            $transformType
        );
    }

    /**
     * Generates a dictionary to be used for conversion.
     * 
     * @return string
     */
    private static function generateDictionary(): string
    {
        return implode(range('a', 'z')).implode(range(0, 9)).implode(range('A', 'Z'));
    }

    /**
     * Although this function's purpose is to just make the ID short - and not so much secure,
     * with $secureKey you can optionally supply a password to make it harder
     * to calculate the corresponding numeric ID.
     *
     *
     * @param string $dictionary
     * @param int    $dictionaryLength
     * @param string $secureKey
     *
     * @return string
     */
    private static function secureEncrypt(string $dictionary, int $dictionaryLength, string $secureKey): string
    {
        $dictionaryArray = str_split($dictionary);
        $secureHash = strlen(hash('sha256', $secureKey)) < $dictionaryLength
            ? hash('sha512', $secureKey)
            : hash('sha256', $secureKey);
        $securedAlphabetArray = str_split(substr($secureHash, 0, $dictionaryLength));
        array_multisort(
            $securedAlphabetArray,
            SORT_DESC,
            $dictionaryArray
        );

        return implode($dictionaryArray);
    }

    /**
     * Converts a short alphanumeric string to a numeric ID.
     * 
     * @param string $input
     * @param string $dictionary
     * @param int    $dictionaryLength
     * @param int    $padUp
     *
     * @return int
     */
    private static function convertToNumber(string $input, string $dictionary, int $dictionaryLength, int $padUp = 0): int
    {
        $result = 0;
        $len = strlen($input) - 1;
        for ($t = $len; $t >= 0; --$t) {
            if (function_exists('bcpow')) {
                $pow = bcpow((string) $dictionaryLength, (string) ($len - $t));
            } else {
                $pow = pow($dictionaryLength, $len - $t);
            }
            $result = $result + strpos($dictionary, substr($input, $t, 1)) * $pow;
        }
        if (--$padUp > 0) {
            $result -= pow($dictionaryLength, $padUp);
        }

        return $result;
    }

    /**
     * Converts a numeric ID to a short alphanumeric string.
     * 
     * @param int    $input
     * @param string $dictionary
     * @param int    $dictionaryLength
     * @param int    $padUp
     *
     * @return string
     */
    private static function convertToAlphanumeric(int $input, string $dictionary, int $dictionaryLength, int $padUp = 0): string
    {
        if (--$padUp > 0) {
            $input += pow($dictionaryLength, $padUp);
        }
        $output = '';
        for ($t = (0 != $input ? floor(log($input, $dictionaryLength)) : 0); $t >= 0; --$t) {
            if (function_exists('bcpow')) {
                $pow = bcpow((string) $dictionaryLength, (string) $t);
            } else {
                $pow = pow($dictionaryLength, $t);
            }
            $a = floor($input / $pow) % $dictionaryLength;
            $output .= substr($dictionary, $a, 1);
            $input -= $a * $pow;
        }

        return $output;
    }

    /**
     * Transforms the input string based on the type.
     * 
     * @param string $input
     * @param int    $transformType
     *
     * @return string
     */
    private static function transformType(string $input, int $type): string
    {
        switch ($type) {
            case self::TRANSFORM_NONE:
                return $input;
            case self::TRANSFORM_UPPERCASE:
                return strtoupper($input);
            case self::TRANSFORM_LOWERCASE:
                return strtolower($input);
            default:
                throw new ConyException(ConyException::E_TRANSFORM_TYPE);
        }
    }
}
