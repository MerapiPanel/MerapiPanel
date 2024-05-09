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


		if ($this->module->getRoles()->isAllowed(0)) {
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

			$roles = json_encode([
				"create" => $this->module->getRoles()->isAllowed(1),
				"update" => $this->module->getRoles()->isAllowed(2),
				"delete" => $this->module->getRoles()->isAllowed(3),
				"modifyTemplate" => $this->module->getRoles()->isAllowed(4)
			]);

			$script = <<<HTML
			<script>
				__.Contact.config = {
					roles: {$roles}
				}
			</script>
			HTML;
			Box::module("Panel")->Scripts->add("contact-opts", $script);
		}

	}


	function index()
	{
		// Box::module("Panel")->setAllowed(false);
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