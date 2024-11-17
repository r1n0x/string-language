---
sidebar_position: 5
---

# Validation

Library includes some runtime validation within a `TokenRunner` and a `ExpressionRunner` but that's sometimes not enough, 
especially if you would like to validate user input without actually running it. There may also be moments when you've 
serialized tokens and will run them later on, and you're not sure if all expressions used within them may still be available.

If you're in need of this kind of validation, there is a validator for that.

:::info
Validator doesn't do any deep validation like expression argument type checking - it only checks if they're available. \
Type checking is only done at runtime (when you run the tokens) and will throw a standard PHP error.
:::

[TODO]