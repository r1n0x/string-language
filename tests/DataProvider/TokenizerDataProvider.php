<?php

namespace R1n0x\StringLanguage\Tests\DataProvider;

use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class TokenizerDataProvider
{
    public static function tokenizes(): array
    {
        return [
            "Empty string" => [
                '',
                []
            ],
            "String containing string" => [
                'test',
                [
                    new StringToken('test')
                ]
            ],
            "String containing special characters" => [
                '!@#$%^&*()[]:;\'"}{\\|<>.~-_=+',
                [
                    new StringToken('!@#$%^&*()[]:;\'"}{\\|<>.~-_=+'),
                ]
            ],
            "String containing multiple strings" => [
                'test test2 test3',
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new StringToken('test2'),
                    new SeparatorToken(),
                    new StringToken('test3'),
                ]
            ],
            "String containing separator" => [
                ' ',
                [
                    new SeparatorToken()
                ]
            ],
            // NO ONE USES MULTIPLE SPACES SERIOUSLY, IF YOU DO YOU DO SOMETHING WRONG
            "String containing multiple separators" => [
                '  ',
                [
                    new SeparatorToken(),
                ]
            ],
            "String containing expression" => [
                'function1(var1, var2)',
                [
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1'),
                            new StringToken('var2')
                        ]
                    )
                ]
            ],
            "String containing expression, which parameters are separated by multiple separators" => [
                'function1(var1,                                     var2)',
                [
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1'),
                            new StringToken('var2')
                        ]
                    )
                ]
            ],
            "String containing expression, which doesn't require any parameters" => [
                'function1()',
                [
                    new ExpressionToken(
                        name: 'function1'
                    )
                ]
            ],
            "String containing nested with parameters" => [
                'function1(function2(var1, var2, var(var3)))',
                [
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new ExpressionToken(
                                name: 'function2',
                                tokens: [
                                    new StringToken('var1'),
                                    new StringToken('var2'),
                                    new ExpressionToken(
                                        name: 'var',
                                        tokens: [
                                            new StringToken('var3'),
                                        ]
                                    )
                                ]
                            )
                        ]
                    )
                ]
            ],
            "String containing multiple expressions" => [
                'function1(var1, var2) function2(var3)',
                [
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1'),
                            new StringToken('var2')
                        ]
                    ),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'function2',
                        tokens: [
                            new StringToken('var3')
                        ]
                    )
                ]
            ],
            // GIGA FEATURE
            "String containing multiple expressions without separator in between" => [
                'function1()function2()',
                [
                    new ExpressionToken(
                        name: 'function1'
                    ),
                    new ExpressionToken(
                        name: 'function2'
                    ),
                ]
            ],
            "String containing brackets" => [
                '()',
                [
                    new StringToken('()')
                ]
            ],
            "String containing string encapsulated in brackets" => [
                '(test)',
                [
                    new StringToken('(test)')
                ]
            ],
            "String containing expression encapsulated in brackets" => [
                '(function1())',
                [
                    new StringToken('('),
                    new ExpressionToken(
                        name: 'function1'
                    ),
                    new StringToken(')'),
                ]
            ],
            "String containing expression encapsulated in multiple brackets" => [
                '((function1()))',
                [
                    new StringToken('(('),
                    new ExpressionToken(
                        name: 'function1'
                    ),
                    new StringToken('))')
                ]
            ],
            "String containing expression encapsulated in quotations" => [
                '"function1(var1)"',
                [
                    new StringToken('"'),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1')
                        ]
                    ),
                    new StringToken('"'),
                ]
            ],
            "String containing expression encapsulated in quotations encapsulated in apostrophes" => [
                '\'"function1(var1)"\'',
                [
                    new StringToken('\'"'),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1')
                        ]
                    ),
                    new StringToken('"\''),
                ]
            ],
            "String containing expression encapsulated in multiple quotations" => [
                '""function1(var1)""',
                [
                    new StringToken('""'),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1')
                        ]
                    ),
                    new StringToken('""')
                ]
            ],
            "String containing expression encapsulated in apostrophes" => [
                "'function1(var1)'",
                [
                    new StringToken("'"),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1')
                        ]
                    ),
                    new StringToken("'"),
                ]
            ],
            "String containing expression encapsulated in apostrophes encapsulated in quotations" => [
                '"\'function1(var1)\'"',
                // ik it doesn't make any sense but hey, it works in the end :)
                [
                    new StringToken('"\''),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1')
                        ]
                    ),
                    new StringToken('\'"')
                ]
            ],
            "String containing expression encapsulated in multiple apostrophes" => [
                "''function1(var1)''",
                [
                    new StringToken("''"),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1')
                        ]
                    ),
                    new StringToken("''"),
                ]
            ],
            "String containing expression encapsulated in separators and brackets" => [
                '( function1() )',
                [
                    new StringToken('('),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'function1'
                    ),
                    new SeparatorToken(),
                    new StringToken(')'),
                ]
            ],
            "String with everything" => [
                'test function1(var1, var2) test2   function2(var3) () (test) (function3(var4)) "test3" \'test4\' \'function4()\' "function5(function6(function7()))" test5 function8(function9(var5)) test6',
                [
                    new StringToken('test'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'function1',
                        tokens: [
                            new StringToken('var1'),
                            new StringToken('var2')
                        ]
                    ),
                    new SeparatorToken(),
                    new StringToken('test2'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'function2',
                        tokens: [
                            new StringToken('var3')
                        ]
                    ),
                    new SeparatorToken(),
                    new StringToken('()'),
                    new SeparatorToken(),
                    new StringToken('(test)'),
                    new SeparatorToken(),
                    new StringToken('('),
                    new ExpressionToken(
                        name: 'function3',
                        tokens: [
                            new StringToken('var4')
                        ]
                    ),
                    new StringToken(')'),
                    new SeparatorToken(),
                    new StringToken('"test3"'),
                    new SeparatorToken(),
                    new StringToken("'test4'"),
                    new SeparatorToken(),
                    new StringToken("'"),
                    new ExpressionToken(
                        name: 'function4'
                    ),
                    new StringToken("'"),
                    new SeparatorToken(),
                    new StringToken('"'),
                    new ExpressionToken(
                        name: 'function5',
                        tokens: [
                            new ExpressionToken(
                                name: 'function6',
                                tokens: [
                                    new ExpressionToken(
                                        name: 'function7',
                                    )
                                ],
                            )
                        ],
                    ),
                    new StringToken('"'),
                    new SeparatorToken(),
                    new StringToken('test5'),
                    new SeparatorToken(),
                    new ExpressionToken(
                        name: 'function8',
                        tokens: [
                            new ExpressionToken(
                                name: 'function9',
                                tokens: [
                                    new StringToken(raw: 'var5')
                                ],
                            )
                        ],
                    ),
                    new SeparatorToken(),
                    new StringToken('test6'),
                ]
            ]
        ];
    }

    public static function throws_an_exception_when_empty_reference_passed_to_expression(): array
    {
        return [
            'String containing expression having one reference, which is empty' => [
                'function( ,)'
            ],
            'String containing nested expression having one reference, which is empty' => [
                'function1(function2( ,))'
            ],
            'String containing nested expression having multiple references of which one is empty' => [
                'function1(function2(var1, ,))'
            ],
            'String containing expression having multiple references, which are empty' => [
                'function( , , , ,)'
            ]
        ];
    }
}