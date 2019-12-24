<?php

namespace app\core\validate;

use Rakit\Validation\Validator as V;

use app\core\validate\UniqueRule;


class Validator
{
	protected $errors = [];

	public function validate($data, $rules, $messages = [])
	{
		$validator = new V;
		$validator->addValidator('unique', new UniqueRule());

		$validation = $validator->make($data, $rules);

		$validation->setMessages($messages);

		$validation->validate();

		if($validation->fails()) {
			$this->errors = $validation->errors();
			return false;
		}

		return true;
	}

	public function getErrors($mode = 'all')
	{
		if ($mode = 'first') {
			$this->errors = $this->errors->firstOfAll();
		} else {
			$this->errors = $this->errors->all();
		}
		$_SESSION['errors'] = $this->errors;
	}
}