<?php


namespace Mindk\Framework\Validation\Rules;


use Mindk\Framework\Validation\AbstractValidationRule;

class LengthBetweenValidationRule extends AbstractValidationRule
{
    /**
     * @inheritdoc
     */
    function check(string $field_name, $field_value, array $params): bool
    {
        $string_length = strlen($field_value);
        return $params[0] <= $string_length && $string_length <= $params[1];
    }

    /**
     * @inheritdoc
     */
    public function getError(string $field_name, $field_value, array $params): string
    {
        return "Length of field $field_name should be between " . $params[0] . " and " . $params[1];
    }
}