<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Router;
use app\models\Main;

use app\core\Db;
use app\models\User;


class AuthController extends Controller
{
	public function register()
	{
		if (!empty($_POST)) {
			$data = $_POST;
			$user = new User();

			if($user->validate($data)) {
				echo 'ес битчес';
			} else {
				$user->getErrors('first');
			}

		}
		$this->view('register', ['old' => $_POST]);

		if (isset($_SESSION['errors'])) unset($_SESSION['errors']);
	}
}