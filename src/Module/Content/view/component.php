<?php

namespace MerapiPanel\Module\Content\View;

use MerapiPanel\Module\ViewEngine\Abstract\ViewComponent;

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
    public function ListPost($order = [
        'DESC',
        'ASC'
    ], $limit = 10)
    {
    }
}
