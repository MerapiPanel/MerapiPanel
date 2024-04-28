<?php
namespace MerapiPanel\Module\Api\Controller {
    
    use MerapiPanel\Box\Module\__Controller;
    use MerapiPanel\Utility\Router;

    class Guest extends __Controller
    {


        function register()
        {

            
            
            Router::GET("/secret/{module}/{method}", "apiCall", self::class);
        }
    }
}