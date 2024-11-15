<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\ExpressionNestLimitReachedException;
use R1n0x\StringLanguage\Exception\InvalidExpressionArgumentException;
use R1n0x\StringLanguage\Internal\Enum\LexerToken;
use R1n0x\StringLanguage\Internal\StringLexer;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;
use R1n0x\StringLanguage\Token\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Tokenizer
{
    private StringLexer $lexer;

    public function __construct()
    {
        $this->lexer = new StringLexer();
    }

    public function __clone(): void
    {
        $this->lexer = clone $this->lexer;
    }

    /**
     * @return array<int, Token>
     *
     * @throws ExpressionNestLimitReachedException
     * @throws InvalidExpressionArgumentException
     */
    public function tokenize(string $string): array
    {
        $this->lexer->setInput($string);
        $this->lexer->moveNext();

        $ret = [];

        while (true) {
            if (!$this->lexer->lookahead) {
                break;
            }

            $this->lexer->moveNext();

            if ($this->lexer->token?->isA(LexerToken::STRING)) {
                $latestArrayKey = array_key_last($ret);
                $lastToken = null !== $latestArrayKey ? $ret[$latestArrayKey] : false;
                if ($lastToken instanceof StringToken) {
                    $ret[(int) $latestArrayKey] = new StringToken($lastToken->getRaw() . $this->lexer->token->value);
                } else {
                    $ret[] = new StringToken((string) $this->lexer->token->value);
                }
            } elseif ($this->lexer->token?->isA(LexerToken::SEPARATOR)) {
                $ret[] = new SeparatorToken();
            } elseif ($this->lexer->token?->isA(LexerToken::EXPRESSION)) {
                $ret[] = new ExpressionToken(
                    name: $this->getExpressionName(),
                    tokens: $this->getExpressionTokens(),
                );
            }
        }

        return $ret;
    }

    /**
     * @return Token[]
     *
     * @throws ExpressionNestLimitReachedException
     * @throws InvalidExpressionArgumentException
     */
    public function getTokens(Tokenizer $tokenizer, string $arguments): array
    {
        return $tokenizer->tokenize($arguments);
    }

    protected function getExpressionName(): string
    {
        return explode('(', (string) $this->lexer->token?->value)[0];
    }

    /**
     * @throws InvalidExpressionArgumentException
     */
    protected function throwIfInvalidExpressionArgument(string $argument): void
    {
        $matches = [];
        $expressionName = $this->getExpressionName();
        // allows spaces so you can pass arguments with spaces to literals,
        // downside is allowing variables with spaces obviously, but I guess that's a feature
        preg_match('/^[a-zA-Z0-9_ ]*$/', $argument, $matches);
        if ('' === $argument || !isset($matches[0]) || $matches[0] !== $argument) {
            throw new InvalidExpressionArgumentException("Expression '$expressionName' argument '$argument' is invalid.");
        }
    }

    /**
     * @return array<int, Token>
     *
     * @throws ExpressionNestLimitReachedException
     * @throws InvalidExpressionArgumentException
     */
    protected function getExpressionTokens(): array
    {
        $ret = [];
        $value = (string) $this->lexer->token?->value;
        $openingPosition = strpos($value, '(') + 1;
        $closingPosition = strrpos($value, ')');
        $arguments = substr($value, $openingPosition, $closingPosition - $openingPosition);
        $tokenizer = clone $this;
        $tokens = $this->mergeArgumentsWhichAreAnSeparatedStrings($this->getTokens($tokenizer, $arguments));
        foreach ($tokens as $token) {
            // trim($token->getRaw()) !== ',' has a "side-effect" which makes function1(function2(var1, ,)) work
            // it merges all contents of function2 (var1, ,) into var1, so it looks like functions takes only one variable
            if ($token instanceof StringToken && ',' !== trim($token->getRaw())) {
                $argument = trim($token->getRaw(), ',');
                $this->throwIfInvalidExpressionArgument($argument);
                $ret[] = new StringToken($argument);
            } elseif ($token instanceof ExpressionToken) {
                $ret[] = $token;
            }
        }

        return $ret;
    }

    /**
     * @param array<int, Token> $tokens
     *
     * @return array<int, Token>
     */
    private function mergeArgumentsWhichAreAnSeparatedStrings(array $tokens): array
    {
        $ret = [];
        $lastToken = null;
        foreach ($tokens as $token) {
            if ($token instanceof SeparatorToken || $token instanceof StringToken) {
                if ($lastToken instanceof StringToken && count(explode(',', $lastToken->getRaw())) <= 1) {
                    unset($ret[array_key_last($ret)]);
                    $mergedToken = new StringToken(
                        raw: $lastToken->getRaw() . ($token instanceof SeparatorToken
                            ? $token->getSeparator()
                            : $token->getRaw())
                    );
                    $ret[] = $mergedToken;
                    $lastToken = $mergedToken;
                    continue;
                }
            }
            $ret[] = $token;
            $lastToken = $token;
        }

        return array_values($ret);
    }
}
