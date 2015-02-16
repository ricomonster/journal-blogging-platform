<?php namespace Journal\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	protected function themes()
	{
		$path = base_path('themes');
		$results = scandir($path);
		$themes = [];

		foreach ($results as $result) {
			if ($result === '.' or $result === '..') continue;
			if (is_dir($path . '/' . $result)) {
				$themes[$result] = ucwords($result);
			}
		}

		return $themes;
	}
}
