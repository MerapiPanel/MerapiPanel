<?php

namespace il4mb\Mpanel\Core\Locale;

use il4mb\Mpanel\Core\AppBox;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Entity extends Translator
{

    protected AppBox $app;

    public function __construct()
    {


        $locale = locale_get_default();
        $locale = explode("_", $locale);

        parent::__construct('en'); // Default locale is 'en'

        if (isset($locale[1])) {
            $this->setLocale(strtolower($locale[1]));
        }

        $this->addLoader('yaml', new YamlFileLoader());

        $this->addResource('yaml', __DIR__ . '/../../Locales/locale.en.yaml', 'en');
        $this->addResource('yaml', __DIR__ . '/../../Locales/locale.fr.yaml', 'fr');
        $this->addResource('yaml', __DIR__ . '/../../Locales/locale.id.yaml', 'id');

    }

}
