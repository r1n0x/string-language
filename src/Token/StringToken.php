<?php

namespace R1n0x\StringLanguage\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class StringToken extends Token
{
    public function __construct(
        private readonly string $raw,
    ) {
    }

    public function getRaw(): string
    {
        return $this->raw;
    }
}
