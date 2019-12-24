<?php

namespace app\controllers;

use app\core\base\Controller;
use app\models\base\Main;

use app\models\User;
use app\core\validate\Validator;


class AuthController extends Controller
{
	public function register()
	{
		if (isset($_SESSION['user'])) redirect('/');

		if (!empty($_POST)) {
			$data = $_POST;
			$modelUser = new User();
			$validator = new Validator();

			
			$rules = $modelUser->getRegRules();
			$messages = $modelUser->getRegMessages();

			if($validator->validate($data, $rules, $messages)) {
				$modelUser->insert($this->getRegData($data));
				redirect();
			} else {
				$validator->getErrors('first');
				$_SESSION['old'] = $data;
			}

		}
		$this->view('register');

		if (isset($_SESSION['errors'])) unset($_SESSION['errors']);
		if (isset($_SESSION['old'])) unset($_SESSION['old']);
	}

	public function login()
	{
		if (isset($_SESSION['user'])) redirect('/');

		if (!empty($_POST)) {
			$data = $_POST;
			$modelUser = new User();
			$validator = new Validator();

			$rules = $modelUser->getLogRules();
			$messages = $modelUser->getLogMessages();

			if($validator->validate($data, $rules, $messages)) {
				$user = $modelUser->where('email', '=', $data['email'])->getAll()[0];
				if (!empty($user)) {
					if (password_verify($data['password'], $user['password'])) {
						//$_SESSION['user'] = $user;
						//unset($_SESSION['user']['password']);
						foreach ($user as $k => $v) {
							if ($k != 'password') $_SESSION['user'][$k] = $v;
						}
						redirect('/');
						return;
					}
				}
				$_SESSION['old'] = $data;
				$_SESSION['errors'][] = 'неверные почта или пароль';
			} else {
				$validator->getErrors('first');
				$_SESSION['old'] = $data;
			}

		}
		$this->view('login');

		if (isset($_SESSION['errors'])) unset($_SESSION['errors']);
		if (isset($_SESSION['old'])) unset($_SESSION['old']);
	}

	public function getRegData($data)
	{
		return [
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => password_hash($data['password'], 1)
		];
	}


}