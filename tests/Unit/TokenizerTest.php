<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Enum\LexerType;
use R1n0x\StringLanguage\Exception\ExpressionNestLimitReachedException;
use R1n0x\StringLanguage\Exception\InvalidExpressionArgumentException;
use R1n0x\StringLanguage\Internal\ExpressionRegexGenerator;
use R1n0x\StringLanguage\Internal\StringLexer;
use R1n0x\StringLanguage\Tests\DataProvider\TokenizerDataProvider;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;
use R1n0x\StringLanguage\Token\Token;
use R1n0x\StringLanguage\Tokenizer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(Tokenizer::class)]
#[CoversClass(StringLexer::class)]
#[UsesClass(ExpressionRegexGenerator::class)]
#[UsesClass(StringToken::class)]
#[UsesClass(SeparatorToken::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(LexerType::class)]
class TokenizerTest extends TestCase
{
    /**
     * @param string $value
     * @param array<int, Token> $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(TokenizerDataProvider::class, 'tokenizes')]
    public function tokenizes(string $value, array $expected): void
    {
        $tokenizer = $this->getTokenizer();
        $this->assertEquals($expected, $tokenizer->tokenize($value));
    }

    #[Test]
    public function throws_exception_when_expressions_are_too_nested(): void
    {
        $this->expectException(ExpressionNestLimitReachedException::class);
        $tokenizer = $this->getTokenizer();
        $string = '';
        for ($i = 0; $i <= (ExpressionRegexGenerator::UNSAFE_EXPRESSION_NEST_AMOUNT); $i++) {
            $string = "function$i($string)";
        }
        $tokenizer->tokenize($string);
    }

    #[Test]
    #[DataProviderExternal(TokenizerDataProvider::class, 'throws_exception_when_empty_reference_passed_to_expression')]
    public function throws_exception_when_empty_reference_passed_to_expression(string $string): void
    {
        $this->expectException(InvalidExpressionArgumentException::class);
        $tokenizer = $this->getTokenizer();
        $tokenizer->tokenize($string);
    }

    public function getTokenizer(): Tokenizer
    {
        return new Tokenizer();
    }
}