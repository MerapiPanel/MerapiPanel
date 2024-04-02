<?php

namespace MerapiPanel\Views\IntlLoader;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class ArrayLoader implements LoaderInterface
{
    public function load($resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        $catalogue = new MessageCatalogue($locale);

        foreach ($resource as $key => $value) {
            $catalogue->set($key, $value, $domain);
        }

        return $catalogue;
    }
}
