<?php

namespace R1n0x\StringLanguage\Tests\DataProvider;

use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class TokenRunnerDataProvider
{
    public static function executes(): array
    {
        return [
            'Tokens containing string' => [
                [
                    new StringToken('123'),
                ],
                [],
                (fn (ExpressionRegistry $registry) => null),
                '123'
            ],
            'Tokens containing separator' => [
                [
                    new SeparatorToken()
                ],
                [],
                (fn (ExpressionRegistry $registry) => null),
                ' '
            ],
            'Tokens containing simple expression' => [
                [
                    new ExpressionToken(
                        name: 'function_name'
                    )
                ],
                [],
                (function (ExpressionRegistry $registry) {
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'function_name';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(): string
                        {
                            return 'test_return';
                        }
                    });
                }),
                'test_return'
            ],
            'Tokens containing nested expression' => [
                [
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new ExpressionToken(name: 'function2'),
                            new ExpressionToken(name: 'function3')
                        ]
                    )
                ],
                [],
                (function (ExpressionRegistry $registry) {
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'function1';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(string $nestedFunctionReturnValue1, string $nestedFunctionReturnValue2): string
                        {
                            return "$nestedFunctionReturnValue1 $nestedFunctionReturnValue2";
                        }
                    });

                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'function2';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(): string
                        {
                            return 'nested_function_return1';
                        }
                    });

                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'function3';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(): string
                        {
                            return 'nested_function_return2';
                        }
                    });
                }),
                'nested_function_return1 nested_function_return2'
            ],
            'Tokens containing advanced method' => [
                [
                    new ExpressionToken(
                        name: 'advanced_function_name',
                        tokens: [
                            new StringToken('name'),
                            new StringToken('id'),
                        ]
                    )
                ],
                [
                    'name' => 'RANDOM_VALUE',
                    'id' => 123
                ],
                (function (ExpressionRegistry $registry) {
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'advanced_function_name';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(string $name, int $id): string
                        {
                            return $name . '_INSERT_' . $id;
                        }
                    });
                }),
                'RANDOM_VALUE_INSERT_123'
            ],
            'Tokens containing full expression' => [
                [
                    new ExpressionToken(
                        name: 'advanced_function_name',
                        tokens: [
                            new StringToken('name'),
                            new StringToken('id'),
                        ]
                    ),
                    new SeparatorToken(),
                    new StringToken('a'),
                    new StringToken('b'),
                    new StringToken('c')
                ],
                [
                    'name' => 'RANDOM_VALUE',
                    'id' => 123
                ],
                (function (ExpressionRegistry $registry) {
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'advanced_function_name';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(string $name, int $id): string
                        {
                            return $name . '_INSERT_' . $id;
                        }
                    });
                }),
                'RANDOM_VALUE_INSERT_123 abc'
            ]
        ];
    }
}