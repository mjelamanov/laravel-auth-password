<?php

namespace Mjelamanov\Laravel\AuthPassword\Rule;

use Illuminate\Contracts\Validation\Rule;

/**
 * Interface RuleFactoryInterface.
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
interface RuleFactoryInterface
{
    /**
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Validation\Rule
     *
     * @throws \InvalidArgumentException If a guard is not registered in the application.
     * @throws \Mjelamanov\Laravel\AuthPassword\Exception\RuleCreationFailedException Whenever it cannot instantiate a rule.
     */
    public function createRule(?string $guard = null): Rule;
}
