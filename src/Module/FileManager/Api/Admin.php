<?php

namespace MerapiPanel\Module\FileManager\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module {



    function uploadEndpoint() {

        $box = $this->getBox();
        return $box->Module_Panel()->adminLink('/filemanager/upload'); 
    }


    public function fetchJsonEndpoint() {
        
       return $this->getBox()->Module_Panel()->adminLink('/filemanager/fetchJson');
    }

}