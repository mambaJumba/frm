<?php

namespace app\core;

use app\core\TwigView;

abstract class Controller
{
	public function view($template, $vars = [])
	{
		$twig = new TwigView;
		echo $twig->render($template, $vars);
	}
}