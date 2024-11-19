<?php

namespace R1n0x\StringLanguage\Tests\DataProvider\Internal\Validator;

use Exception;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionCallValidatorDataProvider
{
    public static function validates(): array
    {
        return [
            'Call which requires no arguments' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: []
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(): void
                    {
                        throw new Exception();
                    }
                },
                [],
            ],
            'Call which requires a single argument' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1): void
                    {
                        throw new Exception();
                    }
                },
                [
                    'var1' => '',
                ],
            ],
            'Call which requires a single, which is not required' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: []
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(?string $var1 = null): void
                    {
                        throw new Exception();
                    }
                },
                [],
            ],
            'Call which requires a single, which is a literal' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1): void
                    {
                        throw new Exception();
                    }

                    public function useStringParametersAsArguments(): bool
                    {
                        return true;
                    }
                },
                [
                    'var1' => '',
                ],
            ],
            'Call which requires multiple arguments' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                        new ExpressionToken(
                            name: 'test2',
                            tokens: [
                                new StringToken('var2'),
                                new StringToken('var3'),
                                new StringToken('var4'),
                            ]
                        ),
                        new StringToken('var5'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1, string $var3, string $var4): void
                    {
                        throw new Exception();
                    }
                },
                [
                    'var1' => '',
                    'var2' => '',
                    'var3' => '',
                    'var4' => '',
                    'var5' => '',
                ],
            ],
            'Call which requires multiple arguments, and last ones are optional' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                        new ExpressionToken(
                            name: 'test2',
                            tokens: [
                                new StringToken('var2'),
                                new StringToken('var3'),
                                new StringToken('var4'),
                            ]
                        ),
                        new StringToken('var5'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1, string $var3, string $var4, ?string $var5 = null, ?string $var6 = null): void
                    {
                        throw new Exception();
                    }
                },
                [
                    'var1' => '',
                    'var2' => '',
                    'var3' => '',
                    'var4' => '',
                    'var5' => '',
                ],
            ],
            'Call has optional parameters in the middle of required ones but all all provided' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                        new StringToken('var2'),
                        new StringToken('var3'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1, ?string $var2 = null, string $var3): void
                    {
                        throw new Exception();
                    }
                },
                [
                    'var1' => '',
                    'var2' => '',
                    'var3' => '',
                ],
            ],
        ];
    }

    public static function throws_an_exception(): array
    {
        return [
            'Call requires less arguments than provided amount' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                        new StringToken('var2'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1): void
                    {
                        throw new Exception();
                    }
                },
                [
                    'var1' => '',
                    'var2' => '',
                ],
            ],
            'Call requires more arguments than provided amount' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: []
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1): void
                    {
                        throw new Exception();
                    }
                },
                [],
            ],
            'Call requires arguments but their values was not provided' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1): void
                    {
                        throw new Exception();
                    }
                },
                [],
            ],
            'Call has optional parameters in the middle of required ones and not all of them were provided' => [
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                        new StringToken('var2'),
                    ]
                ),
                new class extends Expression {
                    public function getExpressionName(): string
                    {
                        return 'test';
                    }

                    public function getMethodName(): string
                    {
                        return 'example';
                    }

                    public function example(string $var1, ?string $var2 = null, string $var3): void
                    {
                        throw new Exception();
                    }
                },
                [
                    'var1' => '',
                    'var2' => '',
                ],
            ],
        ];
    }
}
