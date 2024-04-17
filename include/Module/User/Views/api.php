<?php
namespace MerapiPanel\Module\User\Views;
use MerapiPanel\Box\Module\__Fragment;

class Api extends __Fragment{
    
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module) {
        
    }


    function getAvatar($email, $size = 100) {

        return "https://gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=mp?s=" . $size;
    }
}