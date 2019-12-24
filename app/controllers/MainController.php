<?php

namespace app\controllers;

use app\core\base\Controller;
use app\models\User;


class MainController extends Controller
{
	public function index()
	{
		if (isset($_POST['logout'])) {
			unset($_SESSION['user']);
			redirect('/');
		}
		//dump($_SESSION['user']);

		$this->view('index');
	}
}