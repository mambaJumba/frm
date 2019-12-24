<?php

namespace app\models;

use app\core\base\Model;

class User extends Model
{
	public $table = 'users';

	public function getRegRules()
	{
		return [
			'name' => 'required|min:4|max:30',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|min:6|max:50',
			'confirm_password' => 'required|min:6|max:50|same:password'
		];
	}

	public function getRegMessages()
	{
		return [
			'name:required' => 'имя обязательно к заполнению',
			'name:min' => 'имя должно содержать больше 4 символов'
		];
	}

	public function getLogRules()
	{
		return [
			'email' => 'required|email',
			'password' => 'required|min:6|max:50',
		];
	}

	public function getLogMessages()
	{
		return [
			'email:required' => 'почта обязательно к заполнению',
			'password:min' => 'пароль должен содержать больше 6 символов'
		];
	}
}