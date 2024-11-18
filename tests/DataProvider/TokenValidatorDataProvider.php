<?php

namespace R1n0x\StringLanguage\Tests\DataProvider;

use Exception;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class TokenValidatorDataProvider
{
    public static function validates(): array
    {
        return [
            'When good tokens provided' => [
                (function () {
                    $registry = new ExpressionRegistry();
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test';
                        }

                        public function getMethodName(): string
                        {
                            return 'method';
                        }

                        public function method(string $var1): void
                        {
                            throw new Exception();
                        }
                    });
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test2';
                        }

                        public function getMethodName(): string
                        {
                            return 'method';
                        }

                        public function method(): void
                        {
                            throw new Exception();
                        }
                    });

                    return $registry;
                })(),
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'test',
                        tokens: [
                            new ExpressionToken(
                                name: 'test2',
                                tokens: []
                            ),
                        ]
                    ),
                ],
                0,
            ],
            'When tokens with unknown nested expression provided' => [
                (function () {
                    $registry = new ExpressionRegistry();
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test';
                        }

                        public function getMethodName(): string
                        {
                            return 'method';
                        }

                        public function method(string $var1): void
                        {
                            throw new Exception();
                        }
                    });

                    return $registry;
                })(),
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'test',
                        tokens: [
                            new ExpressionToken(
                                name: 'test2',
                                tokens: []
                            ),
                        ]
                    ),
                ],
                1,
            ],
            'When tokens with unknown nested and non-expression provided' => [
                (function () {
                    return new ExpressionRegistry();
                })(),
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'test',
                        tokens: [
                            new ExpressionToken(
                                name: 'test2',
                                tokens: []
                            ),
                        ]
                    ),
                ],
                2,
            ],
            'When tokens with invalid arguments in non-nested expression provided' => [
                (function () {
                    $registry = new ExpressionRegistry();
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test';
                        }

                        public function getMethodName(): string
                        {
                            return 'method';
                        }

                        public function method(string $var1): void
                        {
                            throw new Exception();
                        }
                    });

                    return $registry;
                })(),
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'test',
                        tokens: []
                    ),
                ],
                1,
            ],
            'When tokens with invalid arguments in nested expression provided' => [
                (function () {
                    $registry = new ExpressionRegistry();
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test';
                        }

                        public function getMethodName(): string
                        {
                            return 'method';
                        }

                        public function method(string $var1): void
                        {
                            throw new Exception();
                        }
                    });
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test2';
                        }

                        public function getMethodName(): string
                        {
                            return 'method';
                        }

                        public function method(string $var2): void
                        {
                            throw new Exception();
                        }
                    });

                    return $registry;
                })(),
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'test',
                        tokens: [
                            new ExpressionToken(
                                name: 'test2',
                                tokens: []
                            ),
                        ]
                    ),
                ],
                1,
            ],
        ];
    }
}
