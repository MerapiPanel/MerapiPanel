<?php
namespace MerapiPanel\Module\HalloWorld;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;

class HalloWorld extends __Fragment {
	protected Module $module;
	function onCreate(Module $module) {
		$this->module = $module;
	}

	// add other funstion here

}