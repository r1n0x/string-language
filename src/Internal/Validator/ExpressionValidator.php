<?php

namespace R1n0x\StringLanguage\Internal\Validator;

use R1n0x\StringLanguage\Exception\NonpublicMethodException;
use R1n0x\StringLanguage\Exception\UndefinedMethodException;
use R1n0x\StringLanguage\Expression\Expression;
use ReflectionException;
use ReflectionMethod;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionValidator
{
    /**
     * @throws NonpublicMethodException
     * @throws UndefinedMethodException
     */
    public function validate(Expression $expression): void
    {
        try {
            $methodName = $expression->getMethodName();
            $methodRef = new ReflectionMethod($expression, $methodName);
            if (!$methodRef->isPublic()) {
                throw new NonpublicMethodException(sprintf("Expression '%s' method named '%s' must be public", get_class($expression), $methodName));
            }
        } catch (ReflectionException $e) {
            throw new UndefinedMethodException(message: sprintf("Expression '%s' doesn't have method named '%s'", get_class($expression), $methodName), previous: $e);
        }
    }
}
