<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\RequiredVariableNotProvidedException;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Exception\UnknownTokenException;
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
        $executor = $this->getExecutor($this->prepareMethodExecutor($registryModifier));
        $this->assertEquals($expected, $executor->run($tokens, $variables));
    }

    #[Test]
    #[TestDox('Throws exception, if one of provided tokens is invalid')]
    public function throws_exception_if_one_of_provided_tokens_is_invalid(): void
    {
        $this->expectException(UnknownTokenException::class);
        $executor = $this->getExecutor();
        $executor->run([new class extends Token {}], []);
    }

    #[Test]
    #[TestDox('Throws exception, if non-nested expression returns unstringable value')]
    public function throws_exception_if_non_nested_expressions_returns_unstringable_value(): void
    {
        $this->expectException(UnknownTokenException::class);
        $executor = $this->getExecutor();
        $executor->run([new class extends Token {}], []);
    }

    private function getExecutor(?ExpressionRunner $runner = null): TokenRunner
    {
        return new TokenRunner($runner ?? new ExpressionRunner(new ExpressionRegistry()));
    }

    private function prepareMethodExecutor(callable $registryModifier): ExpressionRunner
    {
        $registry = new ExpressionRegistry();
        $registryModifier($registry);
        $executor = new ExpressionRunner($registry);;
        return $executor;
    }
}