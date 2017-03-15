<?php


namespace Mindk\Framework\Validation;

/**
 * Class Validator
 * @package Mindk\Framework\Validation
 */
class Validator
{

    private $object;
    private $rules = [];
    private $errors = [];

    private static $known_rules = [
        'min' => 'Mindk\Framework\Validation\Rules\MinValidationRule',
        'numeric' => 'Mindk\Framework\Validation\Rules\NumericValidationRule',
        'length_between' => 'Mindk\Framework\Validation\Rules\LengthBetweenValidationRule',
        'not_start_from' => 'Mindk\Framework\Validation\Rules\NotStartFromValidationRule',
        'required' => 'Mindk\Framework\Validation\Rules\RequiredRule'
    ];

    /**
     * Validator constructor.
     *
     * Example:
     *
     * $validator = new Validator($request, [
     *  "title" => ["length_between:3,10", "not_start_from:M"],
     *  "price" => ["numeric", "min:0"]
     * ]);
     *
     */
    public function __construct($object, array $rules)
    {
        $this->object = $object;
        $this->rules = $rules;
    }

    /**
     * Validates specified object by rules
     *
     * @return bool
     */
    public function validate(): bool
    {
        $result = true;
        foreach ($this->rules as $field_name => $field_rules) {
            foreach ($field_rules as $field_rule) {
                $exploded_rule = explode(":", $field_rule);
                $rule_key = $exploded_rule[0];

                if (!array_key_exists($rule_key, self::$known_rules)) {
                    //throw exception
                    continue;
                }

                $rule_params = [];
                if (count($exploded_rule) > 1) {
                    $rule_params = explode(",", $exploded_rule[1]);
                }

                /** @var $validation_class AbstractValidationRule */
                $validation_class = new self::$known_rules[$rule_key];

                $field_value = isset($this->object->$field_name) ? $this->object->$field_name : null;

                if (!$validation_class->check($field_name, $field_value, $rule_params)) {
                    $result = false;
                    $this->errors[$field_name][] = $validation_class->getError(
                        $field_name, $field_value, $rule_params
                    );
                }


            }

        }
        return $result;

    }

    /**
     * Returns validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Adds new validation rules
     *
     * @param string $key
     * @param string $class_namespace
     * @return bool
     */
    public static function addValidationRule(string $key, string $class_namespace): bool
    {
        if (class_exists($class_namespace)) {
            self::$known_rules[$key] = $class_namespace;
            return true;
        }

        return false;
    }
}