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

    public function getExpressionName(): string
    {
        return explode('(', (string) $this->lexer->token?->value)[0];
    }

    /**
     * @throws InvalidExpressionArgumentException
     */
    public function throwIfInvalidExpressionArgument(string $argument): void
    {
        $matches = [];
        $expressionName = $this->getExpressionName();
        preg_match('/^[a-zA-Z0-9]*$/', $argument, $matches);
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
    private function getExpressionTokens(): array
    {
        $tokens = [];
        $value = (string) $this->lexer->token?->value;
        $openingPosition = strpos($value, '(') + 1;
        $closingPosition = strrpos($value, ')');
        $arguments = substr($value, $openingPosition, $closingPosition - $openingPosition);
        $tokenizer = clone $this;
        foreach ($tokenizer->tokenize($arguments) as $token) {
            if ($token instanceof StringToken) {
                $argument = trim($token->getRaw(), ',');
                $this->throwIfInvalidExpressionArgument($argument);
                $tokens[] = new StringToken($argument);
            } elseif ($token instanceof ExpressionToken) {
                $tokens[] = $token;
            }
        }

        return $tokens;
    }
}
