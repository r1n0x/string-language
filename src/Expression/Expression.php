<?php

namespace R1n0x\StringLanguage\Expression;

abstract class Expression
{
    abstract public function getExpressionName(): string;

    abstract public function getMethodName(): string;

    /**
     * Whenever the strings passed to expression should
     * be used literally (as arguments) or be mapped to variables.
     */
    public function useStringParametersAsArguments(): bool
    {
        return false;
    }
}
