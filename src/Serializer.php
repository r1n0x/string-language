<?php

namespace R1n0x\StringLanguage;

use JMS\Serializer\SerializerBuilder;
use R1n0x\StringLanguage\Token\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Serializer
{
    private \JMS\Serializer\Serializer $serializer;

    public function __construct(?string $cacheDirectory = null)
    {
        $builder = SerializerBuilder::create();
        if ($cacheDirectory) {
            $builder->setCacheDir($cacheDirectory);
        }
        $this->serializer = $builder->build();
    }

    /**
     * @param array<int, Token> $tokens
     */
    public function serialize(array $tokens): string
    {
        return $this->serializer->serialize($tokens, 'json');
    }

    /**
     * @return array<int, Token>
     */
    public function deserialize(string $serializedTokens): array
    {
        // @phpstan-ignore return.type
        return $this->serializer->deserialize($serializedTokens, 'array<int, ' . Token::class . '>', 'json');
    }
}
