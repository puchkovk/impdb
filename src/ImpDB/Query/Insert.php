<?php
/**
 * /ImpDB/Query/Update.php
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

class Insert extends Conditions
{
	/**
	 * @var DB
	 */
	protected $db;
	protected $table;
	protected $columns = [];
	protected $values = [];

	public function __construct(DB $db)
	{
		$this->type = DB::TYPE_INSERT;
		parent::__construct($db);
	}

	/**
	 * @param mixed $table
	 * @return $this
	 */
	public function into($table)
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * @param array $columns
	 * @return $this
	 */
	public function columns(array $columns)
	{
		$this->columns = $columns;

		return $this;
	}

	/**
	 * @param array $values
	 * @return $this
	 */
	public function values(array $values)
	{
		$this->values[] = $values;

		return $this;
	}

	/**
	 * ON DUPLICATE KEY UPDATE implementation
	 * TODO implement it!
	 * @param $pairs
	 * @return $this
	 */
	public function odku($pairs)
	{
		return null;
		//return $this;
	}

	public function build()
	{
		$this->chunks = [
			'INSERT INTO ' . $this->db->prepareName($this->table) . ' ',
		];

		if (!empty($this->columns)) {
			$columns = array_map(function ($column) {
				return $this->db->prepareName($column);
			}, $this->columns);

			$this->chunks[] = '(' . implode(', ', $columns) . ')';
		}
		if (!empty($this->values)) {

			$value_str = [];
			foreach ($this->values as $value_set) {
				$value_str[] = '(' . implode(',', array_map(function ($value) {
						return $this->db->escape($value);
					}, $value_set)) . ')';
			}
			$this->chunks[] = 'VALUES ' . implode(', ', $value_str) . ' ';
		}

		parent::build();
	}
}