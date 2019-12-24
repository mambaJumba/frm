<?php

namespace app\core\twig;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use app\core\twig\TemplateHelper;

class TwigView
{
	protected $loader;
	protected $environment;

	public function __construct() 
	{
		$this->loader = new FilesystemLoader(VIEWS);
		$this->environment = new Environment($this->loader);
		$this->environment->addExtension(new TemplateHelper);
	}

	public function render($template, $vars = [])
	{
		$template = str_replace('.', '/', $template);
		$template = $this->environment->load($template . '.html.twig');
		return $template->render($vars);
	}

}