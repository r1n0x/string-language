<?php

namespace R1n0x\StringLanguage\Internal;

use R1n0x\StringLanguage\Exception\ValidatorException;
use R1n0x\StringLanguage\Expression\Expression;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionValidator
{
    public function validate(Expression $expression): void
    {
        try {
            $methodName = $expression->getMethodName();
            $methodRef = new \ReflectionMethod($expression, $methodName);
            if (!$methodRef->isPublic()) {
                throw new ValidatorException(sprintf("Expression '%s' method named '%s' must be public", get_class($expression), $methodName));
            }
        } catch (\ReflectionException $e) {
            throw new ValidatorException(message: sprintf("Expression '%s' doesn't have method named '%s'", get_class($expression), $methodName), previous: $e);
        }
    }
}
