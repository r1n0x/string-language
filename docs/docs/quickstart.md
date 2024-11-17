---
sidebar_position: 3
---

# Quickstart

Code below shows the principles of this library works, may be a little too much for a quickstart, but it shows you 
most of the functionalities it provides.

Example shows how to create your own expression and introduces concepts such `Tokenizer` which parses your string
into array of tokens which will be later on ran by a `TokenRunner` - it uses `ExpressionRunner` internally for running
all expressions concatenated within a string.

`ExpressionRegistry` is the concept you should remember, it stores all the available expressions - also your own.
For the sake of this example we will also use library provided expression called `LiteralExpression` which allows you to pass 
a static string to a parent expression.

**Tokenizer doesn't support conditionals - only expressions.**

```php
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Expression\LiteralExpression;
use R1n0x\StringLanguage\ExpressionRegistry;
use R1n0x\StringLanguage\ExpressionRunner;
use R1n0x\StringLanguage\Tokenizer;
use R1n0x\StringLanguage\TokenRunner;

class CustomExpression extends Expression {
    public function getExpressionName(): string
    {
        return 'custom';
    }
    
    public function getMethodName(): string
    {
        // name of a method this class has that will be called later on
        return 'example';
    }
    
    // variable names don't have any meaning here, those are just for understandability
    public function example(string $returnValueFromChildExpression, string $variable): string
    {
        return "$returnValueFromChildExpression $variable";
    }
}

$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize('This is custom(literal(an example), var1) you can build');

$registry = new ExpressionRegistry();
$registry->register(new LiteralExpression());
$registry->register(new CustomExpression());

$runner = new TokenRunner(new ExpressionRunner($registry));
$output = $runner->run($tokens, [
    'var1' => 'of what'
]);

echo $output; // prints "This is an example of what you can build"
```