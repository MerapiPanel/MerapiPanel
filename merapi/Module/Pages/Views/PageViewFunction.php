<?php

namespace MerapiPanel\Module\Pages\Views;

use MerapiPanel\Box;
use MerapiPanel\Core\Views\Abstract\ViewFunction;

class PageViewFunction extends ViewFunction
{

    public function getTemplate($templateId)
    {

        return Box::Get($this)->Module_Template()->getTemplateData($templateId);
    }

    
}
