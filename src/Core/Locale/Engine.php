<?php

namespace il4mb\Mpanel\Core\Locale;

use il4mb\Mpanel\Core\Container;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Engine extends Translator
{


    protected Container $app;

    public function __construct(Container $app)
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
