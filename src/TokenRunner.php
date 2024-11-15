<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\TokenRunnerException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;
use R1n0x\StringLanguage\Token\Token;
use Stringable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class TokenRunner
{
    public function __construct(
        private readonly ExpressionRunner $methodExecutor,
    ) {
    }

    /**
     * @param array<int, Token> $tokens
     * @param array<string, mixed> $variables
     *
     * @throws UnknownExpressionException
     * @throws RequiredVariableNotProvidedException
     * @throws UnexpectedToken
     * @throws TokenRunnerException
     */
    public function run(array $tokens, array $variables): string
    {
        $ret = '';
        foreach ($tokens as $token) {
            if ($token instanceof StringToken) {
                $ret .= $token->getRaw();
            } elseif ($token instanceof SeparatorToken) {
                $ret .= $token->getSeparator();
            } elseif ($token instanceof ExpressionToken) {
                $value = $this->methodExecutor->run($token, $variables);
                if (!is_string($value) && !($value instanceof Stringable)) {
                    throw new TokenRunnerException(sprintf("Non-nested expression must return value which is stringable (implements interface '%s')", Stringable::class));
                }
                $ret .= $value;
            } else {
                throw new UnexpectedToken();
            }
        }

        return $ret;
    }
}
