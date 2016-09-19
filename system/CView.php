<?php

namespace system;
use system\SystemController;

class CView
{
	public static function render($path, $data = [])
	{
		$fullPath = __DIR__ . '/../views/' . App::$currentController . '/' . $path . '.php';

		if (!file_exists($fullPath)) {
			throw new \ErrorException('view cannot be found');
		}

		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$$key = $value;
			}
		}

		ob_start();
		include($fullPath);
		$content = ob_get_clean();

		/**
		 * Подключаем Layout
		 */
		ob_start();
		include SystemController::$layout;
		$finalContent = ob_get_clean();

		echo $finalContent;
	}
}