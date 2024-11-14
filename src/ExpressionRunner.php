<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Exception\UnknownTokenException;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionRunner
{
    public function __construct(
        private readonly ExpressionRegistry $registry,
    )
    {
    }

    /**
     * @param array<string, mixed> $variables
     *
     * @returns mixed
     *
     * @throws UnknownExpressionException
     * @throws RequiredVariableNotProvidedException
     * @throws UnknownTokenException
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
     * @throws UnknownTokenException
     *
     * @phpstan-ignore missingType.iterableValue
     */
    private function getArguments(ExpressionToken $expression, array $variables): array
    {
        $values = [];
        foreach ($expression->getTokens() as $token) {
            $values[] = match (true) {
                $token instanceof ExpressionToken => $this->run($token, $variables),
                $token instanceof SeparatorToken => $token->getSeparator(),
                $token instanceof StringToken => $this->getVariable($expression, $token, $variables),
                default => throw new UnknownTokenException(),
            };
        }

        return $values;
    }

    private function getVariable(ExpressionToken $expression, StringToken $token, array $variables)
    {
        if($this->registry->get($expression->getName())->useStringArgumentsAsParameters()) {
            return $token->getRaw();
        }
        return $variables[$token->getRaw()];
    }
}
