<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionRunner
{
    public function __construct(
        private readonly ExpressionRegistry $registry,
    ) {
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @returns mixed
     *
     * @throws UnknownExpressionException
     * @throws RequiredVariableNotProvidedException
     * @throws UnexpectedToken
     */
    public function run(ExpressionToken $token, array $variables): mixed
    {
        $expression = $this->registry->get($token->getName());

        return $expression->{$expression->getMethodName()}(...$this->getArguments($token, $variables));
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
    protected function getArguments(ExpressionToken $expression, array $variables): array
    {
        $arguments = [];
        foreach ($expression->getTokens() as $token) {
            $arguments[] = match (true) {
                $token instanceof ExpressionToken => $this->run($token, $variables),
                $token instanceof StringToken => $this->getVariable($expression, $token, $variables),
                default => throw new UnexpectedToken(),
            };
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
