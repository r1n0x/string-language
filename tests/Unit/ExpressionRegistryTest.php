<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\ExpressionAlreadyRegisteredException;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Expression\LiteralExpression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\Internal\ExpressionValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(ExpressionRegistry::class)]
#[UsesClass(ExpressionValidator::class)]
#[UsesClass(LiteralExpression::class)]
class ExpressionRegistryTest extends TestCase
{
    #[Test]
    #[TestDox('Adds expression to registry')]
    public function adds_expression_to_registry(): void
    {
        $registry = $this->getRegistry();
        $registry->register(new LiteralExpression());
        $this->assertInstanceOf(Expression::class, $registry->get('literal'));
    }

    #[Test]
    #[TestDox('Returns expression from registry')]
    public function returns_method_from_registry(): void
    {
        $registry = $this->getRegistry();
        $registry->register(new LiteralExpression());
        $this->assertInstanceOf(Expression::class, $registry->get('literal'));
    }

    #[Test]
    #[TestDox('Throws an exception when registering expression with same name twice')]
    public function throws_an_exception_when_registering_expression_with_same_name_twice(): void
    {
        $this->expectException(ExpressionAlreadyRegisteredException::class);
        $registry = $this->getRegistry();
        $registry->register(new LiteralExpression());
        $registry->register(new LiteralExpression());
    }

    #[Test]
    #[TestDox('Throws an exception when trying to get an unregistered expression')]
    public function throws_an_exception_when_trying_to_get_an_unregistered_expression(): void
    {
        $this->expectException(UnknownExpressionException::class);
        $registry = $this->getRegistry();
        $registry->get('some_unregistered_method');
    }

    protected function getRegistry(): ExpressionRegistry
    {
        return new ExpressionRegistry();
    }
}