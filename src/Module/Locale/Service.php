<?php

namespace Mp\Module\Locale;

use Mp\Box;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Service extends Translator
{

    protected Box $app;

    public function __construct()
    {


        $locale = locale_get_default();
        $locale = explode("_", $locale);

        parent::__construct('en'); // Default locale is 'en'

        if (isset($locale[1])) {
            $this->setLocale(strtolower($locale[1]));
        }

        $this->addLoader('yaml', new YamlFileLoader());

        $this->addResource('yaml', __DIR__ . '/locales/locale.en.yaml', 'en');
        $this->addResource('yaml', __DIR__ . '/locales/locale.fr.yaml', 'fr');
        $this->addResource('yaml', __DIR__ . '/locales/locale.id.yaml', 'id');

    }

}
