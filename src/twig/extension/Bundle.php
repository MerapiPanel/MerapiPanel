<?php

namespace il4mb\Mpanel\Twig\Extension;

class Bundle extends \Twig\Extension\AbstractExtension
{

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('asset_url', [$this, 'asset_url'])
        ];
    }

    function asset_url($static_url = null)
    {
        return $static_url;

    }

}