<?php
namespace MerapiPanel\Module\Contact\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Admin extends __Controller
{

	function register()
	{

		Router::GET("/contact/template/add", "addTemplate", self::class);
		Router::GET("/contact/template/edit/{id}", "editTemplate", self::class);

		// register other route
		Box::module("Panel")->addMenu([
			"name" => "Contact",
			"link" => Router::GET("/contact", "index", self::class),
			"icon" => "fa-solid fa-phone",
			"children" => [
				[
					"name" => "Template",
					"link" => Router::GET("/contact/template", "template", self::class),
					"icon" => "fa-solid fa-list",
				]
			]
		]);
	}


	function index()
	{
		return View::render("index.html.twig");
	}


	function template()
	{
		return View::render("template.html.twig");
	}

	function addTemplate()
	{
		return View::render("add-template.twig");
	}

	function editTemplate(Request $request)
	{
		$id = $request->id();
		if (!$id) {
			throw new \Exception("Template not found", 404);
		}
		$template = $this->getModule()->Template->fetch($id);
		if (!$template) {
			throw new \Exception("Template not found", 404);
		}

		return View::render("edit-template.twig", [
			"template" => $template
		]);
	}
}