<?php

namespace R1n0x\StringLanguage\Tests\DataProvider;

use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionRunnerDataProvider
{
    public static function runs(): array
    {
        return [
            'Expression without arguments' => [
                function (ExpressionRegistry $registry) {
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
                            return 'return';
                        }
                    });
                },
                new ExpressionToken(
                    name: 'test'
                ),
                [],
                'return',
            ],
            'Expression with arguments' => [
                function (ExpressionRegistry $registry) {
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(int $var): string
                        {
                            return 'return' . $var;
                        }
                    });
                },
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                    ]
                ),
                [
                    'var1' => '1',
                ],
                'return1',
            ],
            'Expression with arguments but using them literally' => [
                function (ExpressionRegistry $registry) {
                    $registry->register(new class extends Expression {
                        public function getExpressionName(): string
                        {
                            return 'test';
                        }

                        public function getMethodName(): string
                        {
                            return 'run';
                        }

                        public function run(string $literal): string
                        {
                            return 'return_' . $literal;
                        }

                        public function useStringParametersAsArguments(): bool
                        {
                            return true;
                        }
                    });
                },
                new ExpressionToken(
                    name: 'test',
                    tokens: [
                        new StringToken('var1'),
                    ]
                ),
                [],
                'return_var1',
            ],
        ];
    }
}
