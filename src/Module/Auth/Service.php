<?php

namespace Mp\Module\Auth;

use Mp\Core\Abstract\Module;

class Service extends Module {
    
    const COOKIE_KEY = "auth";

    public function isAdmin() {

        return isset($_COOKIE[self::COOKIE_KEY]) && $_COOKIE[self::COOKIE_KEY] == "admin";
    }

    public function pathFinder($path) {
        
    }
}