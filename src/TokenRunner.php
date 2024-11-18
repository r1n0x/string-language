<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\TokenRunnerException;
use R1n0x\StringLanguage\Exception\TokenRunnerValidationException;
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
    protected ExpressionRunner $expressionRunner;
    protected TokenValidator $tokenValidator;

    public function __construct(
        protected readonly ExpressionRegistry $registry,
    ) {
        $this->expressionRunner = new ExpressionRunner($registry);
        $this->tokenValidator = new TokenValidator($registry);
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
        $this->validate($tokens);

        $ret = '';
        foreach ($tokens as $token) {
            if ($token instanceof StringToken) {
                $ret .= $token->getRaw();
            } elseif ($token instanceof SeparatorToken) {
                $ret .= $token->getSeparator();
            } elseif ($token instanceof ExpressionToken) {
                $value = $this->expressionRunner->run($token, $variables, false);
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

    /**
     * @param array<int, Token> $tokens
     *
     * @throws TokenRunnerValidationException
     */
    private function validate(array $tokens): void
    {
        $errors = $this->tokenValidator->validate($tokens);
        if (count($errors) > 0) {
            $exception = new TokenRunnerValidationException();
            $exception->setErrors($errors);
            throw $exception;
        }
    }
}
