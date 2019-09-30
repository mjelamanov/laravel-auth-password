<?php

namespace Mjelamanov\Laravel\AuthPassword\Validator;

use Mjelamanov\Laravel\AuthPassword\Rule\RuleFactoryInterface;

/**
 * Class AuthPasswordValidator.
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class AuthPasswordValidator
{
    const RULE_NAME = 'auth_password';

    /**
     * @var \Mjelamanov\Laravel\AuthPassword\Rule\RuleFactoryInterface
     */
    protected $factory;

    /**
     * AuthPasswordValidator constructor.
     *
     * @param \Mjelamanov\Laravel\AuthPassword\Rule\RuleFactoryInterface $factory
     */
    public function __construct(RuleFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @see https://laravel.com/docs/validation#using-extensions
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     *
     * @return bool
     */
    public function validate(string $attribute, $value, array $parameters): bool
    {
        $guard = $parameters[0] ?? null;

        return $this->factory->createRule($guard)
                             ->passes($attribute, $value);
    }
}
