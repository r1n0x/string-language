<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\LogicException;
use R1n0x\StringLanguage\Exception\UnexpectedToken;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\ExpressionRunner;
use R1n0x\StringLanguage\Internal\ExpressionValidator;
use R1n0x\StringLanguage\Tests\DataProvider\ExpressionRunnerDataProvider;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(ExpressionRunner::class)]
#[CoversClass(Expression::class)]
#[UsesClass(ExpressionRegistry::class)]
#[UsesClass(ExpressionValidator::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(Expression::class)]
#[UsesClass(StringToken::class)]
class ExpressionRunnerTest extends TestCase
{
    /**
     * @param callable(ExpressionRegistry $registry): void $registryModifier
     * @param array<string, mixed> $variables
     */
    #[Test]
    #[DataProviderExternal(ExpressionRunnerDataProvider::class, 'runs')]
    public function runs(callable $registryModifier, ExpressionToken $token, array $variables, string $expected): void
    {
        $registry = new ExpressionRegistry();
        $registryModifier($registry);
        $runner = $this->getExpressionRunner($registry);
        $this->assertEquals($expected, $runner->run($token, $variables));
    }

    #[Test]
    #[TestDox('Throws an exception when unexpected token is provided')]
    public function throws_an_exception_when_unexpected_token_is_provided(): void
    {
        $this->expectException(UnexpectedToken::class);
        $registry = new ExpressionRegistry();
        $registry->register(new class extends Expression {
            public function getExpressionName(): string
            {
                return 'test';
            }

            public function getMethodName(): string
            {
                return 'run';
            }

            public function run(): string
            {
                throw new LogicException('this wouldn\'t be executed anyway');
            }
        });
        $runner = $this->getExpressionRunner($registry);
        $runner->run(new ExpressionToken(
            name: 'test',
            tokens: [
                new SeparatorToken(),
            ]
        ), []);
    }

    protected function getExpressionRunner(ExpressionRegistry $registry): ExpressionRunner
    {
        return new ExpressionRunner($registry);
    }
}
