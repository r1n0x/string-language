<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\InvalidExpressionCallException;
use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Internal\Validator\ExpressionCallValidator;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionRunner
{
    protected ExpressionCallValidator $expressionCallValidator;

    public function __construct(
        private readonly ExpressionRegistry $registry,
    ) {
        $this->expressionCallValidator = new ExpressionCallValidator();
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @throws InvalidExpressionCallException
     * @throws RequiredVariableNotProvidedException
     * @throws UnexpectedToken
     * @throws UnknownExpressionException
     */
    public function run(ExpressionToken $token, array $variables, bool $validate = true): mixed
    {
        $expression = $this->registry->get($token->getName());

        return $expression->{$expression->getMethodName()}(...$this->getArguments($token, $expression, $variables, $validate));
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @returns array<int, mixed>
     *
     * @throws RequiredVariableNotProvidedException
     * @throws UnknownExpressionException
     * @throws UnexpectedToken
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function getArguments(ExpressionToken $expressionToken, Expression $expression, array $variables, bool $validate): array
    {
        $arguments = [];
        foreach ($expressionToken->getTokens() as $token) {
            $arguments[] = match (true) {
                $token instanceof ExpressionToken => $this->run($token, $variables, $validate),
                $token instanceof StringToken => $this->getVariable($expressionToken, $token, $variables),
                default => throw new UnexpectedToken(),
            };
        }

        if ($validate) {
            $this->expressionCallValidator->validate($expressionToken, $expression, $variables);
        }

        return $arguments;
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @throws UnknownExpressionException
     */
    protected function getVariable(ExpressionToken $expression, StringToken $token, array $variables): mixed
    {
        if ($this->registry->get($expression->getName())->useStringParametersAsArguments()) {
            return $token->getRaw();
        }

        return $variables[$token->getRaw()];
    }
}
