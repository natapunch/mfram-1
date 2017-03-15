<?php


namespace Mindk\Framework\Validation\Rules;


use Mindk\Framework\Validation\AbstractValidationRule;

class NotStartFromValidationRule extends AbstractValidationRule
{
    /**
     * @inheritdoc
     */
    function check(string $field_name, $field_value, array $params): bool
    {
        return strpos($field_value, $params[0]) !== 0;
    }

    /**
     * @inheritdoc
     */
    public function getError(string $field_name, $field_value, array $params): string
    {
        return "Field $field_name should not start from " . $params[0];
    }
}