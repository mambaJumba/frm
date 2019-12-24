<?php

namespace app\core\base;

use PDO;

class Db
{
	protected $db;
	protected static $instance;

	protected $select = '*';
	protected $table = null;
	protected $where = null;
	protected $limit = null;
	protected $offset = null;
	protected $orderBy = null;
	protected $insertId = null;
	protected $query = null;
	protected $params = [];
	protected $op = ['=', '!=', '<', '>', '<=', '>=', '<>'];


	protected function  __construct() 
	{
		$config = require CFG . '/database.php';

		$dsn = $config['dsn'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'];
		$username = $config['username'];
		$password = $config['password'];

		$options = [
			PDO::ATTR_EMULATE_PREPARES => FALSE,
			PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
		];

		$this->db = new PDO($dsn, $username, $password, $options);
	}

	public static function instance() 
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function execute($sql, $params = [])
	{
		$stmt = $this->db->prepare($sql);
		return $stmt->execute($params);
	}


	public function query($sql, $params = [])
	{
		$stmt = $this->db->prepare($sql);
		$res = $stmt->execute($params);
		if ($res !== false) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	protected function reset()
	{
		$this->select = '*';
		$this->table = null;
		$this->where = null;
		$this->limit = null;
		$this->offset = null;
		$this->orderBy = null;
		$this->insertId = null;
		$this->query = null;
		$this->params = [];
		return;
	}

	public function table($table = '')
	{
		$this->reset();
		$this->table = $table;

		return $this;
	}

	public function select($select = '*')
	{
		$this->select = $select;

		return $this;
	}

	public function getAll()
	{
		$query = 'SELECT ' . $this->select . ' FROM ' . $this->table;

		if (!is_null($this->where)) $query .= $this->where;

		if (!is_null($this->orderBy)) $query .= $this->orderBy;

		if (!is_null($this->limit)) $query .= $this->limit;

		if (!is_null($this->offset)) $query .= $this->offset;

		return $this->query($query, $this->params);
	}

	public function count()
	{
		$query = 'SELECT COUNT(' . $this->select . ') as count FROM ' . $this->table;

		if (!is_null($this->where)) $query .= $this->where;

		return $this->query($query, $this->params)[0]['count'];
	}

	public function where($where, $op = null, $val = null, $andOr = 'AND')
	{
		if (is_null($this->where)) {
			if (in_array($op, $this->op)) {
				$this->where = ' WHERE ' . $where . ' ' . $op . ' ?';
				array_push($this->params, $val);
			}
		} else {
			if (in_array($op, $this->op)) {
				$this->where .= ' ' .  $andOr . ' ' .$where . ' ' . $op . ' ?';
				array_push($this->params, $val);
			}
		}

		return $this;
	}

	public function orWhere($where, $op = null, $val = null)
	{
		$this->where($where, $op, $val, 'OR');

		return $this;
	}

	public function orderBy($column, $sort = 'ASC')
	{
		if (is_null($this->orderBy)) {
			$this->orderBy = ' ORDER BY ' . $column . ' ' . strtoupper($sort);
		} else {
			$this->orderBy .= ', ' . $column . ' ' . strtoupper($sort);
		}

		return $this;
	}

	public function limit($start, $last = null)
	{
		if (is_null($last)) {
			$this->limit = ' LIMIT ?';
			array_push($this->params, $start);
		} else {
			$this->limit = ' LIMIT ?, ?';
			array_push($this->params, $start, $last);
		} 

		return $this;
	}

	public function offset($offset)
	{
		$this->offset = ' OFFSET ?';
		array_push($this->params, $offset);

		return $this;
	}

	public function insert($data = [])
	{
		$columns = ' (' . implode(', ', array_keys($data)) . ') ';

		$arrValues = array_fill(0, count($data), '?');
		$values = 'VALUES (' . implode(', ', $arrValues) . ')';

		$query = 'INSERT INTO ' . $this->table . $columns . $values;

		$stmt = $this->db->prepare($query);
		$insert = $stmt->execute(array_values($data));
		$this->insertId = $this->db->lastInsertId();

		return $insert;
	}

	public function insertId()
	{
		return $this->insertId;
	}

	public function update($data = [])
	{
		$exps = [];
		foreach ($data as $key => $value) {
			$exps[] = $key . ' = ?';
		}
		$expression = implode(', ', $exps);

		$query = 'UPDATE ' . $this->table . ' SET ' . $expression;

		if (!is_null($this->where)) $query .= $this->where;

		if (!is_null($this->limit)) $query .= $this->limit;

		$this->params = array_merge(array_values($data), $this->params);

		return $this->execute($query, $this->params);
	}

	public function delete()
	{
		$query	= 'DELETE FROM ' . $this->table . $this->where;

		return $this->execute($query, $this->params);
	}


}