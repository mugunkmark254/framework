<?php

declare(strict_types=1);

namespace Taydence\Validation;

final class Validator
{
    private array $errors = [];

    public function __construct(
        private readonly array $data
    ) {}

    public function validate(array $rules): array
    {
        foreach ($rules as $field => $ruleSet) {
            $rulesForField = is_string($ruleSet)
                ? explode('|', $ruleSet)
                : $ruleSet;

            $value = $this->data[$field] ?? null;

            if (in_array('nullable', $rulesForField, true) && ($value === null || $value === '')) {
                continue;
            }

            foreach ($rulesForField as $rule) {
                $this->apply($field, $value, $rule);
            }
        }

        if ($this->errors !== []) {
            throw new ValidationException($this->errors);
        }

        return $this->data;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function apply(string $field, mixed $value, string $rule): void
    {
        [$name, $argument] = array_pad(explode(':', $rule, 2), 2, null);
        $empty = $value === null || $value === '';

        if ($name === 'required' && $empty) {
            $this->add($field, 'The field is required.');
            return;
        }

        if ($empty) {
            return;
        }

        match ($name) {
            'string' => is_string($value) ?: $this->add($field, 'The field must be a string.'),
            'integer' => filter_var($value, FILTER_VALIDATE_INT) !== false ?: $this->add($field, 'The field must be an integer.'),
            'numeric' => is_numeric($value) ?: $this->add($field, 'The field must be numeric.'),
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false ?: $this->add($field, 'The field must be a valid email address.'),
            'min' => $this->checkMin($field, $value, (float) $argument),
            'max' => $this->checkMax($field, $value, (float) $argument),
            'in' => in_array((string) $value, explode(',', (string) $argument), true) ?: $this->add($field, 'The selected value is invalid.'),
            'nullable' => null,
            default => null,
        };
    }

    private function checkMin(string $field, mixed $value, float $min): void
    {
        $size = is_string($value) ? strlen($value) : (float) $value;

        if ($size < $min) {
            $this->add($field, "The field must be at least {$min}.");
        }
    }

    private function checkMax(string $field, mixed $value, float $max): void
    {
        $size = is_string($value) ? mb_strlen($value) : (float) $value;

        if ($size > $max) {
            $this->add($field, "The field may not be greater than {$max}.");
        }
    }

    private function add(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}
