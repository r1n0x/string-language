<?php

namespace R1n0x\StringLanguage\Tests\Unit\Internal\Validator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Exception\NonpublicMethodException;
use R1n0x\StringLanguage\Exception\UndefinedMethodException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Internal\Validator\ExpressionValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(ExpressionValidator::class)]
class ExpressionValidatorTest extends TestCase
{
    #[Test]
    #[TestDox('Doesn\'t throw an exception on valid expression')]
    public function doesnt_throw_an_exception_on_valid_expression(): void
    {
        $this->expectNotToPerformAssertions();
        $validator = $this->getValidator();
        $validator->validate(new class extends Expression {
            public function getExpressionName(): string
            {
                return 'test';
            }

            public function getMethodName(): string
            {
                return 'run';
            }

            public function run(): void
            {
            }
        });
    }

    #[Test]
    #[TestDox('Throws an exception, if expression has a undefined method')]
    public function throws_an_exception_if_expression_has_a_undefined_method(): void
    {
        $this->expectException(UndefinedMethodException::class);
        $validator = $this->getValidator();
        $validator->validate(new class extends Expression {
            public function getExpressionName(): string
            {
                return 'test';
            }

            public function getMethodName(): string
            {
                return 'run';
            }
        });
    }

    #[Test]
    #[TestDox('Throws an exception, if expression defined a method, but it\'s not public')]
    public function throws_an_exception_if_expression_method_is_not_public(): void
    {
        $this->expectException(NonpublicMethodException::class);
        $validator = $this->getValidator();
        $validator->validate(new class extends Expression {
            public function getExpressionName(): string
            {
                return 'test';
            }

            public function getMethodName(): string
            {
                return 'run';
            }

            protected function run(): string
            {
                return 'run';
            }
        });
    }

    private function getValidator(): ExpressionValidator
    {
        return new ExpressionValidator();
    }
}
