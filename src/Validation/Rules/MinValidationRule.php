<?php

namespace Mindk\Framework\Validation\Rules;

use Mindk\Framework\Validation\AbstractValidationRule;

class MinValidationRule extends AbstractValidationRule
{

    /**
     * @inheritdoc
     */
    function check(string $field_name, $field_value, array $params): bool
    {
        return floatval($field_value) >= floatval($params[0]);
    }

    /**
     * @inheritdoc
     */
    public function getError(string $field_name, $field_value, array $params): string
    {
        return "Field $field_name should be greater than " . $params[0];
    }


}