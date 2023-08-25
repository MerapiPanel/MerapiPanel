<?php

use il4mb\Mpanel\Application;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

require_once __DIR__ . "/vendor/autoload.php";
/*
$app = new Application();
$app->run();
*/

$translator = new Translator('en'); // Default locale is 'en'
$translator->addLoader('yaml', new YamlFileLoader());

$translator->addResource('yaml', __DIR__ . '/src/Locales/locale.en.yaml', 'en');
$translator->addResource('yaml', __DIR__ . '/src/Locales/locale.fr.yaml', 'fr');


// Assuming translations are stored in resources/translations/messages.en.php

// $translator->addResource('array', [
//     'Hello' => 'Bonjour',
//     'Goodbye' => 'Au revoir',
// ], 'en');

echo $translator->trans('Hello'); // Output: Bonjour
echo "<br>";
echo $translator->trans('Goodbye'); // Output: Au revoir
echo "<br>";

$translator->setLocale('fr'); // Switch to French locale
echo $translator->trans('Hello'); // Output: Bonjour
echo "<br>";
echo $translator->trans('Goodbye'); // Output: Au revoir