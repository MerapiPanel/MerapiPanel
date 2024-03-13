<?php

namespace MerapiPanel\Module\Locale;

use MerapiPanel\Box;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Service extends Translator
{

    protected Box $app;

    public function __construct()
    {

        parent::__construct('en'); // Default locale is 'en'

        $intlLoaded = false;
        foreach (get_loaded_extensions() as $extension) {
            if (strtolower($extension) == "intl") {
                $intlLoaded = true;
                break; // No need to continue the loop once intl is found
            }
        }

        if ($intlLoaded) {
            $locale = locale_get_default();
            $locale = explode("_", $locale);

            if (isset($locale[1])) {
                $this->setLocale(strtolower($locale[1]));
            }
        }

        $this->addLoader('yaml', new YamlFileLoader());

        $this->addResource('yaml', __DIR__ . '/locales/locale.en.yaml', 'en');
        $this->addResource('yaml', __DIR__ . '/locales/locale.fr.yaml', 'fr');
        $this->addResource('yaml', __DIR__ . '/locales/locale.id.yaml', 'id');
    }
}
