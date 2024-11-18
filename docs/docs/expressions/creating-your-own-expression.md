---
sidebar_position: 2
---

# Creating your own expression

If you're in need of your own expression, as you probably should cause library doesn't provide much there are few things
you need to know about.

1. Your expression must extend `R1n0x\StringLanguage\Expression\Expression`.
2. Method name returned within getMethodName() must be public.
3. Optional parameters must be defined last - using them in the middle of at the beginning is forbidden and will throw 
an exception `InvalidExpressionCallException`.

For more examples visit [quickstart page](/string-language/quickstart).