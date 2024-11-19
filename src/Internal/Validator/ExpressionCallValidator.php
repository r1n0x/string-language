<?php

namespace R1n0x\StringLanguage\Internal\Validator;

use R1n0x\StringLanguage\Exception\InvalidExpressionCallException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;
use ReflectionMethod;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionCallValidator
{
    /**
     * @param array<string, mixed> $variables
     *
     * @throws InvalidExpressionCallException
     */
    public function validate(ExpressionToken $expressionToken, Expression $expression, array $variables): void
    {
        $methodName = $expression->getMethodName();
        /**
         * This line won't throw an exception, because {@see ExpressionValidator} was called before this method
         * {@see \R1n0x\StringLanguage\TokenValidator::validate}.
         */
        $methodRef = new ReflectionMethod($expression, $methodName);
        $this->validateNumberOfParameters($expressionToken, $methodRef, $expression);
        $this->validateVariables($expressionToken, $expression, $variables);
    }

    /**
     * @throws InvalidExpressionCallException
     */
    public function validateNumberOfParameters(ExpressionToken $token, ReflectionMethod $methodRef, Expression $expression): void
    {
        $expressionParametersCount = count($token->getTokens());

        // counts optionals in the middle of required ones as required, very nice of you PHP :)
        $requiredParametersCount = $methodRef->getNumberOfRequiredParameters();

        if ($requiredParametersCount !== $expressionParametersCount) {
            throw new InvalidExpressionCallException(sprintf("Expression \'%s\' requires %s arguments, but %s were provided.", $expression->getExpressionName(), $requiredParametersCount, $expressionParametersCount));
        }
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @throws InvalidExpressionCallException
     */
    public function validateVariables(ExpressionToken $expressionToken, Expression $expression, array $variables): void
    {
        $unprovidedParameters = [];
        $providedParameters = [];
        foreach ($expressionToken->getTokens() as $token) {
            if ($token instanceof StringToken && !$expression->useStringParametersAsArguments()) {
                if (!isset($variables[$token->getRaw()])) {
                    $unprovidedParameters[] = $token->getRaw();
                } else {
                    $providedParameters[] = $token->getRaw();
                }
            }
        }
        if (count($unprovidedParameters) > 0) {
            throw new InvalidExpressionCallException(sprintf('Expression \'%s\' requires variables [%s] but only [%s] were provided', $expression->getExpressionName(), implode(', ', array_map(fn (string $name) => sprintf('\'%s\'', $name), $unprovidedParameters)), implode(', ', array_map(fn (string $name) => sprintf('\'%s\'', $name), $providedParameters))));
        }
    }
}
