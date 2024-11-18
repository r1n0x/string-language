<?php

namespace R1n0x\StringLanguage\Internal\Validator;

use R1n0x\StringLanguage\Exception\InvalidExpressionCallException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Token\ExpressionToken;
use ReflectionMethod;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionCallValidator
{
    /**
     * @throws InvalidExpressionCallException
     */
    public function validate(ExpressionToken $token, Expression $expression): void
    {
        $expressionName = $expression->getExpressionName();
        $methodName = $expression->getMethodName();
        /**
         * This line won't throw an exception, because {@see ExpressionValidator} was called before this method
         * {@see \R1n0x\StringLanguage\TokenValidator::validate}.
         */
        $methodRef = new ReflectionMethod($expression, $methodName);
        $expressionParametersCount = count($token->getTokens());

        // counts optionals in the middle of required ones as optional, very nice of you PHP :)
        $requiredParametersCount = $methodRef->getNumberOfRequiredParameters();

        /*
         *  I know this could cause issues, like when you have an optional parameter (even at the end)
         *  better safe than ending on first optional parameter and expecting the dev
         *  to not put required parameters after optional one.
         */
        if ($requiredParametersCount !== $expressionParametersCount) {
            throw new InvalidExpressionCallException("Expression '$expressionName' requires $requiredParametersCount arguments, but $expressionParametersCount were provided.");
        }
    }
}
