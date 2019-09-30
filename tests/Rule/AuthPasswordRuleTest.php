<?php

namespace Mjelamanov\Laravel\AuthPassword\Rule;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Hashing\BcryptHasher;
use Mjelamanov\Laravel\AuthPassword\AbstractAuthPasswordTest;

/**
 * Class AuthPasswordRuleTest
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class AuthPasswordRuleTest extends AbstractAuthPasswordTest
{
    /**
     * @var \Mjelamanov\Laravel\AuthPassword\Rule\RuleFactoryInterface
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->app->make(RuleFactoryInterface::class);
    }

    /**
     * @inheritDoc
     */
    protected function getRule(?string $guard = null)
    {
        return $this->factory->createRule($guard);
    }

    /**
     * @return void
     */
    public function testMessageWithDefaultTranslationKey(): void
    {
        $translator = $this->prophesize(Translator::class);

        $translator->get($this->getTranslationKey())->willReturnArgument(0);

        $rule = new AuthPasswordRule(new BcryptHasher(), $translator->reveal());

        $this->assertEquals($this->getTranslationKey(), $rule->message());
    }

    /**
     * @return void
     */
    public function testMessageShouldReturnCustomMessage(): void
    {
        $customKey = 'validation.custom_key';
        $translator = $this->prophesize(Translator::class);

        $translator->get($customKey)->willReturnArgument(0);

        $rule = new AuthPasswordRule(new BcryptHasher(), $translator->reveal());
        $rule->setTranslationKey($customKey);

        $this->assertEquals($customKey, $rule->message());
    }
}