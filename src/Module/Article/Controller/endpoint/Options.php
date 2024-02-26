<?php

namespace MerapiPanel\Module\Article\Controller\Endpoint;

use MerapiPanel\Box;
use MerapiPanel\Utility\Http\Request;

class Options
{

    function save(Request $req)
    {

        try {
            
            $link_format = $req->link_format();
            $thubnails_enable = $req->thubnails_enable();

            $options = Box::module("article")->getOptions();
            if ($options['link_format'] != $link_format) {
                $options['link_format'] = $link_format;
            }
            if ($options['thubnails_enable'] != $thubnails_enable) {
                $options['thubnails_enable'] = $thubnails_enable;
            }

            return [
                "code" => 200,
                "message" => "Options saved",
            ];

        } catch (\Exception $e) {
            return [
                "code" => 500,
                "message" => $e->getMessage(),
            ];
        }
    }
}