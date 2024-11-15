<?php

namespace R1n0x\StringLanguage\Expression;

abstract class Expression
{
    /**
     * Expression name which would be used within a tokenized string.
     */
    abstract public function getExpressionName(): string;

    /**
     * Method name of this class that would be called on expression execution.
     */
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
