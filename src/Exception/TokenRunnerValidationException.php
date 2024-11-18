<?php

namespace R1n0x\StringLanguage\Exception;

use R1n0x\StringLanguage\ValidationError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @codeCoverageIgnore
 */
class TokenRunnerValidationException extends TokenRunnerException
{
    /**
     * @var array<int, ValidationError>
     */
    protected array $errors = [];

    /**
     * @param array<int, ValidationError> $errors
     *
     * @return $this
     */
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return array<int, ValidationError>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
