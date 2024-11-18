<?php

namespace R1n0x\StringLanguage\Tests\Unit\Internal\Validator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\InvalidExpressionCallException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Internal\Validator\ExpressionCallValidator;
use R1n0x\StringLanguage\Tests\DataProvider\Internal\Validator\ExpressionCallValidatorDataProvider;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(ExpressionCallValidator::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(StringToken::class)]
class ExpressionCallValidatorTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(ExpressionCallValidatorDataProvider::class, 'validates')]
    public function validates(ExpressionToken $token, Expression $expression): void
    {
        $this->expectNotToPerformAssertions();
        $validator = $this->getValidator();
        $validator->validate($token, $expression);
    }

    #[Test]
    #[DataProviderExternal(ExpressionCallValidatorDataProvider::class, 'throws_an_exception')]
    public function throws_an_exception(ExpressionToken $token, Expression $expression): void
    {
        $this->expectException(InvalidExpressionCallException::class);
        $validator = $this->getValidator();
        $validator->validate($token, $expression);
    }

    protected function getValidator(): ExpressionCallValidator
    {
        return new ExpressionCallValidator();
    }
}
