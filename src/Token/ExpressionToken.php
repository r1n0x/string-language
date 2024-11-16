<?php

namespace R1n0x\StringLanguage\Token;

use JMS\Serializer\Annotation\Type;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionToken extends Token
{
    /**
     * @param array<int, Token> $tokens
     */
    public function __construct(
        private readonly string $name,
        #[Type('array<int, ' . Token::class . '>')]
        private readonly array $tokens = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int, Token>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
