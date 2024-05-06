<?php
namespace MerapiPanel\Module\Contact\Views;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;

class Api extends __Fragment
{
	protected $module;
	function onCreate(Module $module)
	{
		$this->module = $module;
	}

	function count()
	{
		return $this->module->count();
	}

	function fetchAll()
	{
		return $this->module->fetchAll();
	}

	function template()
	{
		return new Template($this->module);
	}
}



class Template
{

	protected $module;
	function __construct(Module $module)
	{
		$this->module = $module;
	}

	function count()
	{
		return $this->module->Template->count();
	}

	function fetchAll()
	{
		return $this->module->Template->fetchAll();
	}
}