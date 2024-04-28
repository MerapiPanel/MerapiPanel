<?php
namespace MerapiPanel\Module\Brochure;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;

class Service extends __Fragment
{
	protected Module $module;
	function onCreate(Module $module)
	{
		$this->module = $module;


	}

	// add other funstion here


	public function fetchAll()
	{

		$directory = $_ENV['__MP_CWD__'] . "/content/brochure/";

		// Get an array of PDF files in the directory
		$files = glob($directory . "*.pdf");

		// Map each file to an array containing desired information
		$files_info = array_map(function ($item) {
			// Get the file name without the .pdf extension
			$name = basename($item, ".pdf");

			// Get the relative path of the file within the brochure directory
			$path = str_replace($_ENV['__MP_CWD__'], "", $item);

			// Get the last modification time of the file
			$modified_time = filemtime($item);

			return [
				"name" => $name,
				"path" => $path,
				"modified_time" => $modified_time, // Include modification time in the result
			];
		}, $files);

		// Sort the array based on the 'modified_time' field
		usort($files_info, function ($a, $b) {
			return $a['modified_time'] <=> $b['modified_time'];
		});


		return $files_info;
	}

}