<?php

namespace R1n0x\StringLanguage;

use R1n0x\StringLanguage\Exception\ExpressionAlreadyRegisteredException;
use R1n0x\StringLanguage\Exception\UnknownExpressionException;
use R1n0x\StringLanguage\Expression\Expression;
use R1n0x\StringLanguage\Internal\ExpressionValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionRegistry
{
    /** @var array<string, Expression> */
    private array $expressions = [];

    private ExpressionValidator $validator;

    public function __construct()
    {
        $this->validator = new ExpressionValidator();
    }

    /**
     * @throws ExpressionAlreadyRegisteredException
     */
    public function register(Expression $expression): static
    {
        $this->validator->validate($expression);

        $name = $expression->getExpressionName();

        if ($this->has($name)) {
            throw new ExpressionAlreadyRegisteredException("Expression named '$name' is already registered");
        }

        $this->expressions[$name] = $expression;

        return $this;
    }

    /**
     * @throws UnknownExpressionException
     */
    public function get(string $name): Expression
    {
        if (!$this->has($name)) {
            throw new UnknownExpressionException("Expression named '$name' is not registered");
        }

        return $this->expressions[$name];
    }

    private function has(string $name): bool
    {
        return isset($this->expressions[$name]);
    }
}
