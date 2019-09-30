<?php

namespace Mjelamanov\Laravel\AuthPassword\Rule;

use Prophecy\Argument;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mjelamanov\Laravel\AuthPassword\Exception\RuleCreationFailedException;

/**
 * Class RuleFactoryTest.
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class RuleFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateRuleWithNonExsitenGuard(): void
    {
        $factory = new RuleFactory($this->app);

        $this->expectException(InvalidArgumentException::class);

        $factory->createRule('non_existen');
    }

    /**
     * @return void
     */
    public function testCreateRule(): void
    {
        $factory = new RuleFactory($this->app);

        $rule = $factory->createRule();
        $this->assertInstanceOf(AuthPasswordRule::class, $rule);

        $rule = $factory->createRule('web');
        $this->assertInstanceOf(AuthPasswordRule::class, $rule);

        $rule = $factory->createRule('api');
        $this->assertInstanceOf(AuthPasswordRule::class, $rule);
    }

    /**
     * @return void
     */
    public function testCreateRuleThrowsRuleCreationException(): void
    {
        $container = $this->prophesize(Container::class);
        $container->make(Argument::type('string'))->willThrow(BindingResolutionException::class);

        $factory = new RuleFactory($container->reveal());

        $this->expectException(RuleCreationFailedException::class);

        $factory->createRule();
    }
}
