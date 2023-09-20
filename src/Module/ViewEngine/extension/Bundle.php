<?php

namespace MerapiQu\Module\ViewEngine\Extension;

class Bundle extends \Twig\Extension\AbstractExtension
{

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('assets', [$this, 'assets']),
        ];
    }


    function assets($file = null)
    {

        $root = $_SERVER['DOCUMENT_ROOT'];
        $base = str_replace($root, "", str_replace("\\", '/', realpath(__DIR__ . "/../..")));
        preg_match_all('/\@\w+/ims', $file, $matches);

        if (isset($matches[0][0])) {
            foreach ($matches[0] as $match) {
                $file = str_replace($match, rtrim($base, '/') . "/" . substr($match, 1), $file);
            }
        }

        return $file;
    }
}
