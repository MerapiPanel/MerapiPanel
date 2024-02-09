<?php

namespace MerapiPanel\Module\TemplateEditor\Views;


class Component
{



    /**
     * @label Content list
     * @media <i class='fa-solid fa-bars-staggered'></i>
     * @category Content
     * @return string
     */
    public final function someMethod($options = [
        ['id' => 'text', 'name' => 'Text'],
        ['id' => 'email', 'name' => 'Email'],
        ['id' => 'password', 'name' => 'Password'],
        ['id' => 'number', 'name' => 'Number'],
    ], int $max = 10)
    {

        return "ok";
    }


    function abcd()
    {
    }
}
