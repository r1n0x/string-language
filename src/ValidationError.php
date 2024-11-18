<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Token\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @codeCoverageIgnore
 */
class ValidationError
{
    public function __construct(
        private readonly string $description,
        private readonly Token $token,
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getToken(): Token
    {
        return $this->token;
    }
}
