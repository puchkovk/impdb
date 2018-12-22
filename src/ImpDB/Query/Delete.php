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

class Delete extends Conditions
{
	protected $table;
	protected $set = [];

	/**
	 * Class "Delete" constructor.
	 * @param \ImpDB\DB $db
	 */
	public function __construct(DB $db)
	{
		$this->type = DB::TYPE_DELETE;
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

	public function build()
	{
		$this->chunks = [
			'DELETE FROM',
			$this->db->prepareName($this->table, DB::ESCAPE_ALLOW_ALIAS),
		];

		parent::build();
	}
}