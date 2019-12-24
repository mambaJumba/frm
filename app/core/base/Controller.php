<?php

namespace app\core\base;

use app\core\twig\TwigView;

abstract class Controller
{
	public function view($template, $vars = [])
	{
		$twig = new TwigView;
		echo $twig->render($template, $vars);
	}
}