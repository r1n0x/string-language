<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
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
    public function run(ExpressionToken $expression, array $variables): mixed
    {
        $method = $this->registry->get($expression->getName());

        return $method->{$method->getMethodName()}(...$this->getArguments($expression, $variables));
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
    private function getArguments(ExpressionToken $expression, array $variables): array
    {
        $values = [];
        foreach ($expression->getTokens() as $token) {
            $values[] = match (true) {
                $token instanceof ExpressionToken => $this->run($token, $variables),
                $token instanceof StringToken => $this->getVariable($expression, $token, $variables),
                default => throw new UnexpectedToken(),
            };
        }

        return $values;
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @throws UnknownExpressionException
     */
    private function getVariable(ExpressionToken $expression, StringToken $token, array $variables): mixed
    {
        if ($this->registry->get($expression->getName())->useStringParametersAsArguments()) {
            return $token->getRaw();
        }

        return $variables[$token->getRaw()];
    }
}
