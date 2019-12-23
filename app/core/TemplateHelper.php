<?php

namespace app\core;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;



class TemplateHelper extends AbstractExtension implements GlobalsInterface
{
	public function getFunctions()
	{
		return [
			new TwigFunction('dump', 'dump'),
		];
	}

	public function getGlobals()
	{
		return [
			'session' => $_SESSION,
			'errors' => isset($_SESSION['errors']) ? $_SESSION['errors'] : []
		];
	}

}