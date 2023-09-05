<?php

namespace il4mb\Mpanel\Core\View\Extension;

class Bundle extends \Twig\Extension\AbstractExtension
{

    public function getFilters()
    {
        return [
            new \Twig\TwigTest('test', [$this, 'test']),
            new \Twig\TwigFilter('asset_url', [$this, 'asset_url'])
        ];
    }

    function asset_url($static_url = null)
    {
        return $static_url;
    }

    public function test()
    {
        return 'test';
    }
}
