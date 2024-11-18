<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\TokenRunnerException;
use R1n0x\StringLanguage\Exception\TokenRunnerValidationException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\ExpressionRunner;
use R1n0x\StringLanguage\Internal\Validator\ExpressionCallValidator;
use R1n0x\StringLanguage\Internal\Validator\ExpressionValidator;
use R1n0x\StringLanguage\Tests\DataProvider\TokenRunnerDataProvider;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;
use R1n0x\StringLanguage\Token\Token;
use R1n0x\StringLanguage\TokenRunner;
use R1n0x\StringLanguage\TokenValidator;
use stdClass;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(TokenRunner::class)]
#[CoversClass(ExpressionToken::class)]
#[CoversClass(SeparatorToken::class)]
#[UsesClass(ExpressionRunner::class)]
#[UsesClass(ExpressionValidator::class)]
#[UsesClass(ExpressionRegistry::class)]
#[UsesClass(Expression::class)]
#[UsesClass(StringToken::class)]
#[UsesClass(SeparatorToken::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(TokenValidator::class)]
#[UsesClass(ExpressionCallValidator::class)]
class TokenRunnerTest extends TestCase
{
    #[Test]
    #[TestDox('Runs')]
    #[DataProviderExternal(TokenRunnerDataProvider::class, 'executes')]
    public function runs(array $tokens, array $variables, callable $registryModifier, string $expected): void
    {
        $runner = $this->getTokenRunner($this->prepareExpressionRunner($registryModifier));
        $this->assertEquals($expected, $runner->run($tokens, $variables));
    }

    #[Test]
    #[TestDox('Throws an exception when pre-run validation fails')]
    public function throws_an_exception_when_pre_run_validation_fails(): void
    {
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

                public function run(string $var1): stdClass
                {
                    return new stdClass();
                }
            });
        }));
        try {
            $runner->run([
                new ExpressionToken(
                    name: 'test'
                ),
            ], []);
        } catch (TokenRunnerValidationException $e) {
            $this->assertEquals(1, count($e->getErrors()));
        }
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
            ),
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

    protected function getTokenRunner(?ExpressionRegistry $registry = null): TokenRunner
    {
        return new TokenRunner($registry ?? new ExpressionRegistry());
    }

    protected function prepareExpressionRunner(callable $registryModifier): ExpressionRegistry
    {
        $registry = new ExpressionRegistry();
        $registryModifier($registry);

        return $registry;
    }
}
