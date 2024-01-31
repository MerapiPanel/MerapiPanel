<?php

namespace MerapiPanel\Module\Editor\View;

use Options;
use MerapiPanel\Core\View\Abstract\ViewComponent;

class Component extends ViewComponent
{



    /**
     * @content
     *   <ul>
     *      <li>Content 1</li>
     *      <li>Content 2</li>
     *  </ul>
     * @label Content list
     * @media <i class='fa-solid fa-bars-staggered'></i>
     * @category Content
     * @return string
     */
    public function someMethod($options = [
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
