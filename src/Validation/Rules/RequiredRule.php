<?php


namespace Mindk\Framework\Validation\Rules;


use Mindk\Framework\Validation\AbstractValidationRule;

class RequiredRule extends AbstractValidationRule
{
    /**
     * @inheritdoc
     */
    function check(string $field_name, $field_value, array $params): bool
    {
        return !is_null($field_value) && $field_value !== "";
    }

    /**
     * @inheritdoc
     */
    public function getError(string $field_name, $field_value, array $params): string
    {
        return "Field $field_name is required";
    }

}