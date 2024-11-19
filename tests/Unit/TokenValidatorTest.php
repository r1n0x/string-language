<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\Internal\Validator\ExpressionCallValidator;
use R1n0x\StringLanguage\Internal\Validator\ExpressionValidator;
use R1n0x\StringLanguage\Tests\DataProvider\TokenValidatorDataProvider;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\Token;
use R1n0x\StringLanguage\TokenValidator;
use R1n0x\StringLanguage\ValidationError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(TokenValidator::class)]
#[UsesClass(ExpressionRegistry::class)]
#[UsesClass(ExpressionCallValidator::class)]
#[UsesClass(ExpressionValidator::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(ValidationError::class)]
class TokenValidatorTest extends TestCase
{
    /**
     * @param array<int, Token> $tokens
     * @param array<string, mixed> $variables
     */
    #[Test]
    #[DataProviderExternal(TokenValidatorDataProvider::class, 'validates')]
    public function validates(ExpressionRegistry $registry, array $tokens, array $variables, int $expectedErrorCount): void
    {
        $tokenValidator = new TokenValidator($registry);
        $this->assertEquals($expectedErrorCount, count($tokenValidator->validate($tokens, $variables)));
    }
}
