<?php

namespace app\core\base;

abstract class Model
{
	protected $db;
	public $table;

	public function __construct()
	{
		$this->db = Db::instance();
	}

	public function __call($name, $arguments)
	{
		return call_user_func_array([$this->db->table($this->table), $name], $arguments);
	}
}