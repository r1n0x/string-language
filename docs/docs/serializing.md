---
sidebar_position: 5
---

# Serializing

Library provides its own Serializer, which internally heavily relies on [JMS Serializer](https://github.com/schmittjoh/serializer), 
which gives it an ability to serialize and deserialize tokens.

Example belows shows how to serialize and deserialize tokens with ease - for the purposes of understandability and consistency 
we'll use parts of the example from [Quickstart page](/string-language/quickstart). 

```php
use R1n0x\StringLanguage\Serializer;
use R1n0x\StringLanguage\Tokenizer;

$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize('This is custom(literal(an example), var1) you can build');

$serializer = new Serializer();
$serializedTokens = $serializer->serialize($tokens); // returns json
$deserializedTokens = $serializer->deserialize($serializedTokens); // same value as $tokens
```

As you can see, tokenizing and serializing doesn't require providing `ExpressionRegistry`, so as you can probably guess,
expressions are not validated anywhere here - for that you should use library provided [validation](/string-language/validation) 
methods, or if you can - just run the tokens. Running them uses the same validation schema internally so there is no instances 
where you run your tokens, and they throw an exception in the middle of something important.