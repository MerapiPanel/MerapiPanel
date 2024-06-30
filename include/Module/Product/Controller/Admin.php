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
		if (!$this->module->getRoles()->isAllowed(0)) {
			return;
		}

		$index = Router::GET("/product", [$this, 'index']);
		Router::GET("/product/new", [$this, 'new']);
		Router::GET("/product/edit/{id}", [$this, 'edit']);
		Router::GET("/product/view/{id}", [$this, 'view']);
		// register other route

		Box::module("Panel")->addMenu([
			"order" => 2,
			"name" => "Product",
			"link" => $index->__toString(),
			"icon" => "fa-solid fa-box",
		]);

		$roles = json_encode([
			"modify" => $this->module->getRoles()->isAllowed(1),
		]);

		$config = $this->module->getConfig()->__toString();
		$script = <<<HTML
		<script>
			__.MProduct.endpoints = {
				fetchAll: 	"{{ '/api/Product/fetchAll' | access_path }}",
				fetch: 	 	"{{ '/api/Product/fetch' | access_path }}",
				delete: 	"{{ '/api/Product/delete' | access_path }}",
				add: 		"{{ '/api/Product/add' | access_path }}",
				update: 	"{{ '/api/Product/update' | access_path }}",

				view: "{{ '/product/view/{id}' | access_path }}",
				edit: "{{ '/product/edit/{id}' | access_path }}",
			}
			__.MProduct.config = {
				...$config,
				roles: $roles,
				...{
					payload: {
						limit: 10,
						page: 1,
					}
				}
			}
		</script>
		HTML;
		Box::module("Panel")->Scripts->add("product-opts", $script);
	}
	function index()
	{
		return View::render("index");
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