<?php

namespace Mjelamanov\Laravel\AuthPassword\Validator;

use Mjelamanov\Laravel\AuthPassword\AbstractAuthPasswordTest;

/**
 * Class AuthPasswordValidatorTest.
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class AuthPasswordValidatorTest extends AbstractAuthPasswordTest
{
    /**
     * {@inheritdoc}
     */
    protected function getRule(?string $guard = null)
    {
        if ($guard) {
            return $this->getRuleName() . ':' . $guard;
        }

        return $this->getRuleName();
    }

    /**
     * @return void
     */
    public function testAuthPasswordCustomValidationMessage(): void
    {
        $this->app->setLocale('en');

        $overriddenMessage = 'Your password is not valid';
        $v = $this->validator->make([], ['password' => $this->getRule()], [$this->getRuleName() => $overriddenMessage]);

        $this->assertEquals($overriddenMessage, $v->errors()->first('password'));
    }

    /**
     * @return string
     */
    protected function getRuleName(): string
    {
        return AuthPasswordValidator::RULE_NAME;
    }
}
