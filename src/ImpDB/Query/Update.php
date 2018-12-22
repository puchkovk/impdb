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

class Update extends Conditions
{
	protected $table;
	protected $set = [];

	public function __construct(DB $db)
	{
		$this->type = DB::TYPE_UPDATE;
		parent::__construct($db);
	}

	/**
	 * @param string $table
	 * @return $this
	 */
	public function table($table)
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @param array $pairs
	 * @return $this
	 */
	public function set(array $pairs)
	{
		$this->set = $pairs;
		return $this;
	}

	public function build()
	{
		$this->chunks = [
			'UPDATE',
			$this->db->prepareName($this->table, DB::ESCAPE_ALLOW_ALIAS),
			'SET',
		];

		$set = [];
		foreach ($this->set as $field => $value) {
			$set[] = $this->db->prepareName($field).' = '.$this->db->escape($value);
		}

		$this->chunks[] = implode(', ', $set);

		parent::build();
	}
}