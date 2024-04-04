<?php
namespace MerapiPanel\Module\HalloWorld\Controller;

use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Admin extends __Controller
{

	function register()
	{
		Router::GET("/HalloWorld", "index", self::class);
		// register other route
	}
	function index()
	{
		return View::render("index.html.twig");
	}
}