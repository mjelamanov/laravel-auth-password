<?php

namespace Mjelamanov\Laravel\AuthPassword;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Translation\TranslationServiceProvider as OriginalTranslationServiceProvider;
use Mjelamanov\Laravel\AuthPassword\Rule\AuthPasswordRule;
use Orchestra\Testbench\TestCase;

/**
 * Class AbstractAuthPasswordTest
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
abstract class AbstractAuthPasswordTest extends TestCase
{
    use WithFaker;

    /**
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $validator;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->app['validator'];
        $this->hasher = $this->app['hash'];
        $this->translator = $this->app['translator'];
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * @param string|null $guard
     *
     * @return \Illuminate\Contracts\Validation\Rule|string
     */
    abstract protected function getRule(?string $guard = null);

    /**
     * @inheritDoc
     */
    protected function overrideApplicationProviders($app)
    {
        return [
            OriginalTranslationServiceProvider::class => TranslationServiceProvider::class,
        ];
    }

    /**
     * @return void
     */
    public function testAuthPasswordRuleIsImplicit(): void
    {
        $v = $this->validator->make([], ['password' => $this->getRule()]);

        $this->assertTrue($v->fails());
    }

    /**
     * @param mixed $password
     *
     * @return void
     *
     * @dataProvider invalidPasswordsProvider
     */
    public function testAuthPasswordWithEmptyPasswords($password): void
    {
        $v = $this->validator->make(compact('password'), ['password' => $this->getRule()]);

        $this->assertTrue($v->fails());
    }

    /**
     * @param mixed $password
     *
     * @return void
     *
     * @dataProvider invalidPasswordsProvider
     */
    public function testAuthPasswordWithEmptyArrayPasswords($password): void
    {
        $v = $this->validator->make(['user' => compact('password')], ['user.password' => $this->getRule()]);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['users' => [$password]], ['users.*' => $this->getRule()]);
        $this->assertTrue($v->fails());
    }

    /**
     * @param mixed $password
     *
     * @return void
     *
     * @dataProvider invalidPasswordsProvider
     */
    public function testAuthPasswordWithEmptyPasswordWhenUserIsAuthenticated($password): void
    {
        $this->actingAs($this->mockUser($this->faker->password));

        $v = $this->validator->make(compact('password'), ['password' => $this->getRule()]);

        $this->assertTrue($v->fails());
    }

    /**
     * @param mixed $password
     *
     * @return void
     *
     * @dataProvider invalidPasswordsProvider
     */
    public function testAuthPasswordWithEmptyArrayPasswordsWhenUserIsAuthenticated($password): void
    {
        $this->actingAs($this->mockUser($this->faker->password));

        $v = $this->validator->make(['user' => compact('password')], ['user.password' => $this->getRule()]);
        $this->assertTrue($v->fails());

        $v = $this->validator->make(['users' => [$password]], ['users.*' => $this->getRule()]);
        $this->assertTrue($v->fails());
    }

    /**
     * @return void
     */
    public function testAuthPasswordIsValid(): void
    {
        $password = $this->faker->password;

        $this->actingAs($this->mockUser($password));

        $v = $this->validator->make(compact('password'), ['password' => $this->getRule()]);
        $this->assertFalse($v->fails());

        $v = $this->validator->make(['user' => compact('password')], ['user.password' => $this->getRule()]);
        $this->assertFalse($v->fails());

        $v = $this->validator->make(['users' => [$password]], ['users.*' => $this->getRule()]);
        $this->assertFalse($v->fails());
    }

    /**
     * @return void
     */
    public function testAuthPasswordIsValidWithDifferentGuards(): void
    {
        $password = $this->faker->password;

        $this->actingAs($this->mockUser($password), 'api');

        $v = $this->validator->make(compact('password'), ['password' => $this->getRule('api')]);
        $this->assertFalse($v->fails());

        $v = $this->validator->make(compact('password'), ['password' => $this->getRule('web')]);
        $this->assertTrue($v->fails());
    }

    /**
     * @return void
     */
    public function testAuthPasswordValidationMessage(): void
    {
        $this->app->setLocale('en');

        $v = $this->validator->make([], ['password' => $this->getRule()]);

        $expectedMessage = $this->translator->get($this->getTranslationKey(), ['attribute' => 'password']);

        $this->assertNotEquals($this->getTranslationKey(), $expectedMessage);
        $this->assertEquals($expectedMessage, $v->errors()->first('password'));
    }

    /**
     * @return array
     */
    public function invalidPasswordsProvider(): array
    {
        return [
            [''],
            [null],
            [123456],
            [true],
            [false],
            [[]],
        ];
    }

    /**
     * @param string $password
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function mockUser(string $password): Authenticatable
    {
        return (new User())->setAttribute('password', $this->hashPassword($password));
    }

    /**
     * @param string $password
     *
     * @return string
     */
    protected function hashPassword(string $password): string
    {
        return $this->hasher->make($password);
    }

    /**
     * @return string
     */
    protected function getTranslationKey(): string
    {
        return AuthPasswordRule::TRANSLATION_KEY;
    }
}