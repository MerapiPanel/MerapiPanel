<?php
namespace MerapiPanel\Module\Contact\Controller;

use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Admin extends __Controller
{

	function register()
	{
		Router::GET("/Contact", "index", self::class);
		// register other route
	}
	function index()
	{
		return View::render("index.html.twig");
	}
}