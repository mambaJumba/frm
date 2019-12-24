<?php

namespace app\core\validate;

use Rakit\Validation\Rule;
use app\core\base\Db;

class UniqueRule extends Rule
{
	protected $message = ":attribute :value has been used";
	protected $fillableParams = ['table', 'column'];

	public function check($value): bool
	{
		$this->requireParameters(['table', 'column']);

		$column = $this->parameter('column');
		$table = $this->parameter('table');

		$db = Db::instance();
		$count = $db->table($table)->where($column, '=', $value)->count();

		return intval($count) === 0;
		
	}
}