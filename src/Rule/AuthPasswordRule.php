<?php

namespace Mjelamanov\Laravel\AuthPassword\Rule;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\ImplicitRule;
use Mjelamanov\Laravel\AuthPassword\Validator\AuthPasswordValidator;

/**
 * Class AuthPasswordRule.
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class AuthPasswordRule implements ImplicitRule
{
    const TRANSLATION_KEY = 'validation.' . AuthPasswordValidator::RULE_NAME;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $authUser;

    /**
     * @var string
     */
    protected $translationKey = self::TRANSLATION_KEY;

    /**
     * AuthPasswordRule constructor.
     *
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     * @param \Illuminate\Contracts\Translation\Translator $translator
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $authUser
     */
    public function __construct(Hasher $hasher, Translator $translator, ?Authenticatable $authUser = null)
    {
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->authUser = $authUser;
    }

    /**
     * @param string $translationKey
     *
     * @return AuthPasswordRule
     */
    public function setTranslationKey(string $translationKey): AuthPasswordRule
    {
        $this->translationKey = $translationKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        if (! $this->authUser) {
            return false;
        }

        if (! is_string($value) || empty($value)) {
            return false;
        }

        return $this->checkPassword($value);
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return $this->translator->get($this->translationKey);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function checkPassword(string $value): bool
    {
        return $this->hasher->check($value, $this->getAuthPassword());
    }

    /**
     * @return string
     */
    protected function getAuthPassword(): string
    {
        return $this->authUser->getAuthPassword();
    }
}
