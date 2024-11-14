<?php

namespace R1n0x\StringLanguage\Internal;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionRegexGenerator
{
    public const UNSAFE_EXPRESSION_NEST_AMOUNT = self::PREG_SPLIT_MAX_REGEX_NEST;
    public const SAFE_EXPRESSION_NEST_AMOUNT = self::PREG_SPLIT_MAX_REGEX_NEST - 1;
    private const PREG_SPLIT_MAX_REGEX_NEST = 249;

    public function generate(int $max): string
    {
        $ret = '';

        for ($i = 0; $i < $max; ++$i) {
            $ret = '(?:[^()]|\(' . $ret . '*\))';
        }

        return "\b\w+\($ret*\)";
    }
}
