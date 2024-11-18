<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Exception\ValidatorException;
use R1n0x\StringLanguage\Internal\Validator\ExpressionCallValidator;
use R1n0x\StringLanguage\Internal\Validator\ExpressionValidator;
use R1n0x\StringLanguage\Token\ExpressionToken;
use R1n0x\StringLanguage\Token\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class TokenValidator
{
    protected ExpressionValidator $expressionValidator;
    protected ExpressionCallValidator $expressionCallValidator;

    public function __construct(
        private readonly ExpressionRegistry $registry,
    ) {
        $this->expressionValidator = new ExpressionValidator();
        $this->expressionCallValidator = new ExpressionCallValidator();
    }

    /**
     * @param array<int, Token> $tokens
     *
     * @return array<int, ValidationError>
     */
    public function validate(array $tokens): array
    {
        $errors = [];
        foreach ($tokens as $token) {
            if (!($token instanceof ExpressionToken)) {
                continue;
            }
            $expressionName = $token->getName();
            try {
                $expression = $this->registry->get($expressionName);
                $this->expressionValidator->validate($expression);
                $this->expressionCallValidator->validate($token, $expression);
            } catch (UnknownExpressionException|ValidatorException $e) {
                $errors[] = new ValidationError(
                    description: $e->getMessage(),
                    token: $token
                );
            }
            $errors = [...$errors, ...$this->validate($token->getTokens())];
        }

        return $errors;
    }
}
