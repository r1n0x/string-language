---
sidebar_position: 4
---

# Validation

Library includes validation within `TokenRunner` but that's sometimes not enough, especially if you would like to validate 
user input without actually running it. There may also be moments when you've serialized tokens and will run them later on, 
and you're not sure if all expressions used within them may still be available and want to handle things gracefully.

If you're in need of this kind of validation, there is a validator for that.

Validating variables is currently not supported.

:::info
Validator doesn't do any deep validation like expression argument type checking - it only checks if they're available
and if you've provided all the required arguments.

**Type checking** is only done at runtime (when you run the tokens) and will throw a standard PHP error.
:::

```php
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\Tokenizer;
use R1n0x\StringLanguage\TokenValidator;

$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize('This is custom(literal(an example), var1) you can build');

$registry = new ExpressionRegistry();
$validator = new TokenValidator($registry);

$errors = $validator->validate($tokens);

print_r($errors); // prints 2 errors cause expressions literal and custom are not registered
```

`TokenValidator` method `validate` returns an array of `ValidationError` instances, from those you can get a whole token,
which caused the issue and a detailed description about the problem.

Validation errors caused within a TokenRunner will cause an exception `TokenRunnerValidationException` to be thrown,
from it, you can get all the standard validation errors like the above.