<?php

namespace R1n0x\StringLanguage\Tests\Unit\Expression;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Expression\LiteralExpression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\ExpressionRunner;
use R1n0x\StringLanguage\Internal\Validator\ExpressionCallValidator;
use R1n0x\StringLanguage\Internal\Validator\ExpressionValidator;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(LiteralExpression::class)]
#[UsesClass(ExpressionRegistry::class)]
#[UsesClass(ExpressionRunner::class)]
#[UsesClass(ExpressionValidator::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(StringToken::class)]
#[UsesClass(ExpressionCallValidator::class)]
class LiteralExpressionTest extends TestCase
{
    #[Test]
    public function returns_literal(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(new LiteralExpression());
        $runner = new ExpressionRunner($registry);
        $this->assertEquals('VAR_NAME', $runner->run(new ExpressionToken(
            name: 'literal',
            tokens: [
                new StringToken('VAR_NAME'),
            ]
        ), []));
    }
}
