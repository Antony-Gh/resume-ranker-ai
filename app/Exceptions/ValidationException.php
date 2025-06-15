<?php

namespace App\Exceptions;

use Illuminate\Validation\Validator;

class ValidationException extends ApiException
{
    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * ValidationException constructor.
     *
     * @param array|string $errors
     * @param string $message
     * @param array $headers
     * @param \Throwable|null $previous
     */
    public function __construct(
        array|string $errors,
        string $message = 'The given data was invalid',
        array $headers = [],
        ?\Throwable $previous = null
    ) {
        if (is_string($errors)) {
            $this->errors = ['general' => [$errors]];
        } elseif ($errors instanceof Validator) {
            $this->errors = $errors->errors()->toArray();
        } else {
            $this->errors = $errors;
        }

        parent::__construct($message, 422, ['errors' => $this->errors], $headers, $previous);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
