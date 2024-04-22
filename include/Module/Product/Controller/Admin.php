<?php
namespace MerapiPanel\Module\Product\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Admin extends __Controller
{

	function register()
	{

		$index = Router::GET("/product", "index", self::class);
		Router::GET("/product/new", "new", self::class);
		Router::GET("/product/edit/{id}", "edit", self::class);
		Router::GET("/product/view/{id}", "view", self::class);
		// register other route

		Box::module("Panel")->addMenu([
			"order" => 2,
			"name" => "Product",
			"link" => $index->__toString(),
			"icon" => "fa-solid fa-box",
		]);
	}
	function index()
	{
		return View::render("index.html.twig");
	}


	function view(Request $request)
	{

		$id = $request->id();
		$data = [];
		$product = $this->module->fetch(["id", "title", "price", "category", "description", "data", "status"], $id);
		if ($product) {
			$data['product'] = $product;
		}

		return View::render("view.html.twig", $data);
	}

	function new()
	{
		return View::render("editor.html.twig");
	}

	function edit(Request $request)
	{

		$id = $request->id();
		$product = $this->module->fetch(["id", "title", "price", "category", "description", "data", "status"], $id);
		if (!$product) {
			throw new \Exception("Product not found", 404);
		}

		return View::render("editor.html.twig", [
			"product" => $product
		]);
	}
}