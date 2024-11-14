<?php

namespace R1n0x\StringLanguage\Expression;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariableExpression extends Expression
{
    private const EXPRESSION_NAME = 'var';

    public function getExpressionName(): string
    {
        return self::EXPRESSION_NAME;
    }

    public function getMethodName(): string
    {
        return 'run';
    }

    public function run(mixed $value): mixed
    {
        return $value;
    }
}
