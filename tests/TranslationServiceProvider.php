<?php

namespace Mjelamanov\Laravel\AuthPassword;

use Illuminate\Translation\FileLoader;

/**
 * Class TranslationServiceProvider
 *
 * @author Mirlan Jelamanov <mirlan.jelamanov@gmail.com>
 */
class TranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    const TEST_TRANSLATIONS_PATH = __DIR__ . '/resources/lang';

    /**
     * @inheritDoc
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], static::TEST_TRANSLATIONS_PATH);
        });
    }
}