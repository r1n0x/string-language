<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\TokenRunnerException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\ExpressionRunner;
use R1n0x\StringLanguage\Internal\ExpressionValidator;
use R1n0x\StringLanguage\Tests\DataProvider\TokenRunnerDataProvider;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;
use R1n0x\StringLanguage\Token\Token;
use R1n0x\StringLanguage\TokenRunner;
use stdClass;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(TokenRunner::class)]
#[UsesClass(ExpressionRunner::class)]
#[UsesClass(ExpressionValidator::class)]
#[UsesClass(ExpressionRegistry::class)]
#[UsesClass(Expression::class)]
#[UsesClass(StringToken::class)]
#[UsesClass(SeparatorToken::class)]
#[UsesClass(ExpressionToken::class)]
class TokenRunnerTest extends TestCase
{
    #[Test]
    #[TestDox('Executes')]
    #[DataProviderExternal(TokenRunnerDataProvider::class, 'executes')]
    public function executes(array $tokens, array $variables, callable $registryModifier, string $expected): void
    {
        $runner = $this->getTokenRunner($this->prepareExpressionRunner($registryModifier));
        $this->assertEquals($expected, $runner->run($tokens, $variables));
    }

    #[Test]
    #[TestDox('Throws an exception, if non-nested expression doesn\'t return a string')]
    public function throws_an_exception_if_non_nested_expression_doesnt_return_a_string(): void
    {
        $this->expectException(TokenRunnerException::class);
        $runner = $this->getTokenRunner($this->prepareExpressionRunner(function (ExpressionRegistry $registry) {
            $registry->register(new class extends Expression {
                public function getExpressionName(): string
                {
                    return 'test';
                }

                public function getMethodName(): string
                {
                    return 'run';
                }

                public function run(): stdClass
                {
                    return new stdClass();
                }
            });
        }));
        $runner->run([
            new ExpressionToken(
                name: 'test'
            )
        ], []);
    }

    #[Test]
    #[TestDox('Throws an exception, if one of provided tokens is invalid')]
    public function throws_an_exception_if_one_of_provided_tokens_is_invalid(): void
    {
        $this->expectException(UnexpectedToken::class);
        $runner = $this->getTokenRunner();
        $runner->run([new class extends Token {
        }], []);
    }

    #[Test]
    #[TestDox('Throws an exception, if non-nested expression returns unstringable value')]
    public function throws_an_exception_if_non_nested_expressions_returns_unstringable_value(): void
    {
        $this->expectException(UnexpectedToken::class);
        $runner = $this->getTokenRunner();
        $runner->run([new class extends Token {
        }], []);
    }

    protected function getTokenRunner(?ExpressionRunner $runner = null): TokenRunner
    {
        return new TokenRunner($runner ?? new ExpressionRunner(new ExpressionRegistry()));
    }

    protected function prepareExpressionRunner(callable $registryModifier): ExpressionRunner
    {
        $registry = new ExpressionRegistry();
        $registryModifier($registry);
        $executor = new ExpressionRunner($registry);;
        return $executor;
    }
}