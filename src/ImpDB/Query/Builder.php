<?php
/**
 * /ImpDB/Query/Builder.php
 *
 * Copyright © 2018 Konstantin Puchkov. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 */

namespace ImpDB\Query;

use ImpDB\Exception\QueryException;
use ImpDB\Result;

class Builder
{
	/**
	 * @var \ImpDB\DB
	 */
	protected $db;
	protected $type;
	protected $query;
	protected $chunks = [];
	protected $error = '';
	protected $errno = 0;

	/**
	 * @var bool
	 */
	protected $is_built = FALSE;

	public function __construct(\ImpDB\DB $db)
	{
		$this->db = $db;
	}

	/**
	 * @return Result
	 * @throws \ImpDB\Exception\DbException
	 */
	public function execute()
	{
		if (!$this->is_built) {
			$this->build();
		}

		return $this->db->query($this->type, $this->query);
	}

	public function __toString()
	{
		if (!$this->is_built) {
			try {
				$this->build();
			} catch (QueryException $e) {
				$this->error = $e->getMessage();
				$this->errno = $e->getCode();
				return 'Query build error ' . $this->errno . ': ' . $this->error;
			}
		}
		return $this->query;
	}

	public function getErrno()
	{
		return $this->errno;
	}

	public function getError()
	{
		return $this->error;
	}

	public function queryRaw($query)
	{
		$this->query = $query;
		$this->is_built = true;
		return $this;
	}

	protected function build()
	{
		$this->query = implode(' ', $this->chunks);
		$this->is_built = true;
	}
}