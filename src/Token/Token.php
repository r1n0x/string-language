<?php

namespace R1n0x\StringLanguage\Token;

use JMS\Serializer\Annotation\Discriminator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @codeCoverageIgnore
 */
#[Discriminator(map: [
    'expression' => ExpressionToken::class,
    'separator' => SeparatorToken::class,
    'string' => StringToken::class,
])]
abstract class Token
{
}
