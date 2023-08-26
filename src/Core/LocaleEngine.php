<?php

namespace il4mb\Mpanel\Core;

use Exception;
use il4mb\Mpanel\Application;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class LocaleEngine extends Translator
{


    protected Application $app;

    public function __construct(Application $app)
    {

        $this->app = $app;

        $locale = locale_get_default();
        $locale = explode("_", $locale);

        parent::__construct('en'); // Default locale is 'en'

        if (isset($locale[1])) {
            $this->setLocale(strtolower($locale[1]));
        }

        $this->addLoader('yaml', new YamlFileLoader());

      
        $this->addResource('yaml', $app->getDirectory() . '/Locales/locale.en.yaml', 'en');
        $this->addResource('yaml', $app->getDirectory() . '/Locales/locale.fr.yaml', 'fr');
        $this->addResource('yaml', $app->getDirectory() . '/Locales/locale.id.yaml', 'id');
    }
}
