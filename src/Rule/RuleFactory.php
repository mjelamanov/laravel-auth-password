<?php

namespace Mjelamanov\Laravel\AuthPassword\Rule;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mjelamanov\Laravel\AuthPassword\Exception\RuleCreationFailedException;

/**
 * Class RuleFactory.
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class RuleFactory implements RuleFactoryInterface
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * RuleFactory constructor.
     *
     * @param \Illuminate\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function createRule(?string $guard = null): Rule
    {
        try {
            $authUser = $this->resolveAuthUser($guard);

            return $this->container->make(AuthPasswordRule::class, compact('authUser'));
        } catch (BindingResolutionException $e) {
            throw new RuleCreationFailedException('Unable to failed to create an rule', 0, $e);
        }
    }

    /**
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function resolveAuthUser(?string $guard = null): ?Authenticatable
    {
        $guard = $this->resolveGuard($guard);

        return $guard->user();
    }

    /**
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Auth\Guard
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function resolveGuard(?string $guard = null): Guard
    {
        return $this->getAuthFactory()
            ->guard($guard);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Factory
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getAuthFactory(): Factory
    {
        return $this->container->make(Factory::class);
    }
}
