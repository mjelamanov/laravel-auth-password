<?php

namespace Mjelamanov\Laravel\AuthPassword;

use Illuminate\Support\ServiceProvider as Provider;
use Mjelamanov\Laravel\AuthPassword\Rule\RuleFactory;
use Mjelamanov\Laravel\AuthPassword\Rule\RuleFactoryInterface;
use Mjelamanov\Laravel\AuthPassword\Validator\AuthPasswordValidator;

/**
 * Class ServiceProvider
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class ServiceProvider extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidatorExtension();
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->registerRuleFactory();
    }

    /**
     * @return void
     */
    protected function registerValidatorExtension(): void
    {
        $this->app['validator']->extendImplicit(
            AuthPasswordValidator::RULE_NAME, AuthPasswordValidator::class . '@validate'
        );
    }

    /**
     * @return void
     */
    protected function registerRuleFactory(): void
    {
        $this->app->singleton(RuleFactoryInterface::class, RuleFactory::class);
    }
}