<?php
namespace MerapiPanel\Module\Product\Views\Admin;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;

class Api extends __Fragment
{
	protected $module;
	function onCreate(Module $module)
	{
		$this->module = $module;
	}

	function isAllowed($id)
	{
		return $this->module->getRoles()->isAllowed($id);
	}

}