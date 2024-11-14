<?php

namespace R1n0x\StringLanguage\Expression;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LiteralExpression extends Expression
{
    private const EXPRESSION_NAME = 'literal';

    public function getExpressionName(): string
    {
        return self::EXPRESSION_NAME;
    }

    public function getMethodName(): string
    {
        return 'run';
    }

    public function run(string $value): string
    {
        return $value;
    }

    public function useStringArgumentsAsParameters(): bool
    {
        return true;
    }
}
