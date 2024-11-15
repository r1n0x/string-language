<?php

namespace R1n0x\StringLanguage\Internal;

use Doctrine\Common\Lexer\AbstractLexer;
use R1n0x\StringLanguage\Exception\ExpressionNestLimitReachedException;
use R1n0x\StringLanguage\Internal\Enum\LexerToken;

/**
 * @internal
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @extends AbstractLexer<LexerToken, string>
 */
class StringLexer extends AbstractLexer
{
    private ExpressionRegexGenerator $methodRegexGenerator;

    public function __construct()
    {
        $this->methodRegexGenerator = new ExpressionRegexGenerator();
    }

    /**
     * @throws ExpressionNestLimitReachedException
     */
    public function setInput(string $input): void
    {
        $this->throwIfInputIsUnsafe($input);
        parent::setInput($input);
    }

    protected function getCatchablePatterns(): array
    {
        return [
            $this->methodRegexGenerator->generate(ExpressionRegexGenerator::SAFE_EXPRESSION_NEST_AMOUNT),
            '(?<!,)[\s\n\r]+', // token separator (DOESN'T resolve function separators)
        ];
    }

    protected function getNonCatchablePatterns(): array
    {
        return [];
    }

    protected function getType(string &$value): LexerToken
    {
        if (preg_match('/\w\(/', $value)) {
            return LexerToken::EXPRESSION;
        }
        if ('' === trim($value)) {
            return LexerToken::SEPARATOR;
        }

        return LexerToken::STRING;
    }

    /**
     * @throws ExpressionNestLimitReachedException
     */
    private function throwIfInputIsUnsafe(string $input): void
    {
        $unsafeMatches = [];
        preg_match('/' . $this->methodRegexGenerator->generate(ExpressionRegexGenerator::UNSAFE_EXPRESSION_NEST_AMOUNT) . '/', $input, $unsafeMatches);
        $safeMatches = [];
        preg_match('/' . $this->methodRegexGenerator->generate(ExpressionRegexGenerator::SAFE_EXPRESSION_NEST_AMOUNT) . '/', $input, $safeMatches);

        if (count($unsafeMatches) > 0
            && count($safeMatches) > 0
            && $unsafeMatches[0] !== $safeMatches[0]) {
            throw new ExpressionNestLimitReachedException('You can nest methods "only" up to ' . ExpressionRegexGenerator::SAFE_EXPRESSION_NEST_AMOUNT);
        }
    }
}
