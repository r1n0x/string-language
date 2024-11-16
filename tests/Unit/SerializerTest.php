<?php

namespace R1n0x\StringLanguage\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\StringLanguage\Serializer;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\SeparatorToken;
use R1n0x\StringLanguage\Token\StringToken;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[CoversClass(Serializer::class)]
#[UsesClass(ExpressionToken::class)]
#[UsesClass(StringToken::class)]
#[UsesClass(SeparatorToken::class)]
class SerializerTest extends TestCase
{
    protected function setUp(): void
    {
        register_shutdown_function(function () {
            $this->removeDirectory($this->getCacheDirectory());
        });
    }

    #[Test]
    #[TestDox('Serializes empty array of tokens')]
    public function serializes_empty_array_of_tokens(): void
    {
        $serializer = $this->getSerializer();
        $this->assertEquals('[]', $serializer->serialize([]));
    }

    #[Test]
    #[TestDox('Serializes array with of tokens')]
    public function serializes_array_with_of_tokens(): void
    {
        $serializer = $this->getSerializer();
        $this->assertEquals('[{"raw":"test1","type":"string"},{"type":"separator"},{"name":"function1","tokens":{},"type":"expression"},{"type":"separator"},{"name":"function2","tokens":{"0":{"name":"function3","tokens":{"0":{"name":"function4","tokens":{"0":{"raw":"var1","type":"string"},"1":{"name":"function5","tokens":{"0":{"raw":"var2","type":"string"},"1":{"raw":"var3 with spaces","type":"string"}},"type":"expression"}},"type":"expression"}},"type":"expression"}},"type":"expression"},{"type":"separator"},{"raw":"test2","type":"string"}]', $serializer->serialize([
            new StringToken('test1'),
            new SeparatorToken(),
            new ExpressionToken(
                name: 'function1',
                tokens: []
            ),
            new SeparatorToken(),
            new ExpressionToken(
                name: 'function2',
                tokens: [
                    new ExpressionToken(
                        name: 'function3',
                        tokens: [
                            new ExpressionToken(
                                name: 'function4',
                                tokens: [
                                    new StringToken('var1'),
                                    new ExpressionToken(
                                        name: 'function5',
                                        tokens: [
                                            new StringToken('var2'),
                                            new StringToken('var3 with spaces'),
                                        ]
                                    ),
                                ]
                            ),
                        ]
                    ),
                ]
            ),
            new SeparatorToken(),
            new StringToken('test2'),
        ]));
    }

    #[Test]
    #[TestDox('Deserializes')]
    public function deserializes(): void
    {
        $serializer = $this->getSerializer();
        $this->assertEquals([
            new StringToken('test1'),
            new SeparatorToken(),
            new ExpressionToken(
                name: 'function1',
                tokens: []
            ),
            new SeparatorToken(),
            new ExpressionToken(
                name: 'function2',
                tokens: [
                    new ExpressionToken(
                        name: 'function3',
                        tokens: [
                            new ExpressionToken(
                                name: 'function4',
                                tokens: [
                                    new StringToken('var1'),
                                    new ExpressionToken(
                                        name: 'function5',
                                        tokens: [
                                            new StringToken('var2'),
                                            new StringToken('var3 with spaces'),
                                        ]
                                    ),
                                ]
                            ),
                        ]
                    ),
                ]
            ),
            new SeparatorToken(),
            new StringToken('test2'),
        ], $serializer->deserialize('[{"raw":"test1","type":"string"},{"type":"separator"},{"name":"function1","tokens":{},"type":"expression"},{"type":"separator"},{"name":"function2","tokens":{"0":{"name":"function3","tokens":{"0":{"name":"function4","tokens":{"0":{"raw":"var1","type":"string"},"1":{"name":"function5","tokens":{"0":{"raw":"var2","type":"string"},"1":{"raw":"var3 with spaces","type":"string"}},"type":"expression"}},"type":"expression"}},"type":"expression"}},"type":"expression"},{"type":"separator"},{"raw":"test2","type":"string"}]'));
    }

    protected function getSerializer(): Serializer
    {
        return new Serializer($this->getCacheDirectory());
    }

    protected function getCacheDirectory(): string
    {
        return sys_get_temp_dir() . '/string-language-serializer-test-cache';
    }

    /**
     * Special thanks to https://stackoverflow.com/a/1653776.
     */
    protected function removeDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ('.' == $item || '..' == $item) {
                continue;
            }

            if (!$this->removeDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }
}
