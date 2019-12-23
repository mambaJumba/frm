<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
	public $table = 'users';

	public $rules = [
		'name' => 'required|min:4|max:30',
		'email' => 'required|email',
		'password' => 'required|min:6|max:50',
		'confirm_password' => 'required|min:6|max:50|same:password'
	];

	public $messages = [
		'name:required' => 'Имя обязательно'
	];

}