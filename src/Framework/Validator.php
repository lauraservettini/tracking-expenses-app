<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationException;

class Validator
{
    private array $rules = [];

    public function add(string $alias, RuleInterface $rule)
    {
        $this->rules[$alias] = $rule;
    }

    public function validate(array $formData, array $fields)
    {
        // creare un array $errors multidimensionale per salvare gli errori da far vedere nei relativi campi del form
        $errors = [];

        foreach ($fields as $fieldName => $rules) {
            foreach ($rules as $rule) {
                $ruleValidator = $this->rules[$rule];

                // se la validazione ha successo va avanti con continue
                if ($ruleValidator->validate($formData, $fieldName, [])) {
                    continue;
                } else {
                    $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, []);
                }
            }
        }
        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }
}
