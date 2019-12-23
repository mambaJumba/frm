<?php

namespace app\core;

use Rakit\Validation\Validator;

abstract class Model
{
	protected $db;
	protected $table;
	protected $errors = [];
	public $rules = [];
	public $messages = [];

	public function __construct()
	{
		$this->db = Db::instance();
	}

	public function __call($name, $arguments)
	{
		$this->db->table($this->table);
	}

	public function validate($data)
	{
		$validator = new Validator;

		$validation = $validator->make($data, $this->rules);

		if(!empty($this->messages)) {
			$validation->setMessages($this->messages);
		}

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