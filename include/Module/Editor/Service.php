<?php
namespace MerapiPanel\Module\Editor;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Views\View;

class Service extends __Fragment {
	protected Module $module;
	function onCreate(Module $module) {
		// error_log("Editor::onCreate");
		$this->module = $module;
		View::getInstance()->getTwig()->addExtension(new EditorExtension());
	}

	// add other funstion here

}