<?php

namespace R1n0x\StringLanguage\Tests\Unit\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Internal\ExpressionRegexGenerator;
use R1n0x\StringLanguage\Tests\DataProvider\Internal\ExpressionRegexGeneratorDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(ExpressionRegexGenerator::class)]
class ExpressionRegexGeneratorTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(ExpressionRegexGeneratorDataProvider::class, 'generates_regex')]
    public function generates_regex(int $nestLevel, string $expected)
    {
        $generator = new ExpressionRegexGenerator();
        $this->assertEquals($expected, $generator->generate($nestLevel));
    }
}