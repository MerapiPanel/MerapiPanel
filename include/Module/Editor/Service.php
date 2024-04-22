<?php
namespace MerapiPanel\Module\Editor;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Views\View;

class Service extends __Fragment
{
	protected Module $module;
	function onCreate(Module $module)
	{
		// error_log("Editor::onCreate");
		$this->module = $module;
		View::getInstance()->getTwig()->addExtension(new EditorExtension());
	}

	// add other funstion here

	public function findComponent($components = [], $type, $deep = 5)
	{

		foreach ($components as $component) {

			if (isset($component['type']) && $component['type'] == $type) {
				return $component;
			}
			if ($deep > 0 && isset($component['components'])) {
				$component = $this->findComponent($component['components'], $type, $deep - 1);
				if ($component) {
					return $component;
				}
			}
		}


		return null;
	}


	function getAttribute($component, $attr)
	{

		
	}

}