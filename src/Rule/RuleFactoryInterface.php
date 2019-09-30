<?php

namespace Mjelamanov\Laravel\AuthPassword\Rule;

use Illuminate\Contracts\Validation\Rule;

/**
 * Interface RuleFactoryInterface
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
interface RuleFactoryInterface
{
    /**
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Validation\Rule
     */
    public function createRule(?string $guard = null): Rule;
}