<?php
namespace MerapiPanel\Module\Editor\Controller;

use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Admin extends __Controller
{

	function register()
	{
		Router::GET("/editor/api/load", "apiLoad", self::class);
		Router::POST("/editor/api/save", "apiSave", self::class);
		Router::GET("/Editor", "index", self::class);
		// register other route
	}
	function index()
	{
		return View::render("index.html.twig");
	}

	function apiLoad()
	{

		return [
			"code" => 200,
			"message" => "success",
			"data" => $this->getModule()->Blocks->getBlocks() ?? [],
		];
	}

	function apiSave(Request $request)
	{

		return [
			"code" => 200,
			"message" => "success, it's sample from editor api, you have to chnage save endpoint",
			"data" => $request->data() ?? [],
		];
	}
}