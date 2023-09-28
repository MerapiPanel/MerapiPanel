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
     * @media <i style='font-size: 2rem; padding: 1rem' class='fa-solid fa-bars-staggered'></i>
     * @category Content
     * @return string
     */
    public function ListPost($order = [
        'DESC',
        'ASC'
    ], $limit = 10)
    {


        if ($this->getPayload()) {

            $data  = $this->getPayload();
            $order = $data['order'];
            $limit = (int)$data['limit'];

            $content = "<ul>";
            for ($i = 0; $i < ($limit < 3 ? 3 : ($limit > 10 ? 10 : $limit)); $i++) {
                $content .= "<li>Content " . $i + 1 . "</li>";
            }
            $content .= "</ul>";

            return $content;
        }

        return "Content list";
    }
}
