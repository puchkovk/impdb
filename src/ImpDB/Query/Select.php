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

class Select extends Conditions
{
	protected $tableName;
	protected $columns = [];


	public function __construct(DB $db)
	{
		$this->type = DB::TYPE_SELECT;
		parent::__construct($db);
	}

	public function columns($columns)
	{
		$this->columns = $columns;
		return $this;
	}

	public function from($table)
	{
		$this->tableName = $table;
		return $this;
	}

	protected function build()
	{
		$this->chunks = ['SELECT'];

		if (!empty($this->columns)) {
			$columns = array_map(function ($column) {
				return $this->db->prepareName($column, DB::ESCAPE_ALLOW_ALIAS | DB::ESCAPE_ALLOW_ASTERISK);
			}, $this->columns);

			$this->chunks[] = implode(', ', $columns);
		} else {
			$this->chunks[] = '*';
		}

		$this->chunks[] = 'FROM ' . $this->db->prepareName($this->tableName, DB::ESCAPE_ALLOW_ALIAS);

		parent::build();
	}
}