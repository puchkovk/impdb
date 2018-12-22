<?php
/**
 * /ImpDB/Query/Join.php
 *
 * Copyright © 2018 Konstantin Puchkov. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 */

namespace ImpDB\Query;

use ImpDB\DB;

class Join
{
	/**
	 * @var DB
	 */
	protected $db;
	protected $type;
	protected $table;
	protected $on = [];

	public function __construct(DB $db)
	{
		$this->db = $db;
	}

	public function table($table)
	{
		$this->table = $table;
	}

	public function type($type)
	{
		$this->type = strtoupper($type);
	}

	public function on($column, $op, $column2)
	{
		$this->on[] = [$column, $op, $column2];
	}

	public function asString()
	{
		$sql = (empty($this->type) ? '' : ($this->type . ' ')) . 'JOIN ';

		$sql .= $this->db->prepareName($this->table, DB::ESCAPE_ALLOW_ALIAS) . ' ON (';
		foreach ($this->on as $on) {
			list($column, $op, $column2) = $on;
			$sql .= $this->db->prepareName($column);
			$sql .= ' ' . $op;
			$sql .= ' ' . $this->db->prepareName($column2);
		}
		return $sql . ')';
	}
}