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
                $ruleParams = [];

                // verifica se ci sono parametri nella rule
                if (str_contains($rule, ":")) {
                    [$rule, $ruleParams] = explode(":", $rule);
                    $ruleParams = explode(",", $ruleParams);
                }

                $ruleValidator = $this->rules[$rule];

                // se la validazione ha successo va avanti con continue
                if ($ruleValidator->validate($formData, $fieldName, $ruleParams)) {
                    continue;
                } else {
                    $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, $ruleParams);
                }
            }
        }
        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }
}
