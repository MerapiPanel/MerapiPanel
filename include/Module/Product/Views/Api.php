<?php
namespace MerapiPanel\Module\Product\Views;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;

class Api extends __Fragment
{
	protected $module;
	function onCreate(Module $module)
	{
		$this->module = $module;
	}

	function fetchAll() {
		return $this->module->fetchAll();
	}
}