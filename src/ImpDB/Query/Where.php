<?php
/**
 * Copyright © 2018. Konstantin Puchkov. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 * File: ImpDB/Query/Where
 */

namespace ImpDB\Query;


use ImpDB\DB;

class Where
{
	/**
	 * @var DB
	 */
	protected $db;

	protected $column;
	protected $op;
	protected $value;
	protected $orWhere = false;
	protected $bracketOpen = false;
	protected $bracketClose = false;

	public function __construct(DB $db)
	{
		$this->db = $db;
	}

	public function isBracketOpen()
	{
		return $this->bracketOpen;
	}

	public function isBracketClose()
	{
		return $this->bracketClose;
	}

	public function bracketOpen()
	{
		$this->bracketOpen = true;

		return $this;
	}

	public function orBracketOpen()
	{
		$this->bracketOpen = true;
		$this->orWhere = true;

		return $this;
	}

	public function bracketClose()
	{
		$this->bracketClose = true;

		return $this;
	}

	public function orWhere()
	{
		$this->orWhere = true;

		return $this;
	}

	public function isOr()
	{
		return $this->orWhere;
	}

	/**
	 * @param mixed $column
	 * @return $this
	 */
	public function column($column)
	{
		$this->column = $column;

		return $this;
	}

	/**
	 * @param string $op
	 * @return $this
	 */
	public function op($op)
	{
		$this->op = $op;

		return $this;
	}

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function value($value)
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * @return string
	 * @throws \ImpDB\Exception\DbException
	 * @throws \ImpDB\Exception\QueryException
	 */
	public function build()
	{
		$result = '';

		if ($this->bracketOpen) {
			$result .= '(';
		} elseif ($this->bracketClose) {
			$result .= ')';
		} else {
			$column = $this->db->prepareName($this->column);
			$op = $this->db->escape($this->op, false);

			if (is_array($this->value)) {
				$value = "('" . implode("', '", array_map(function($val) {
						return $this->db->escape($val, false);
					}, $this->value)) . "')";
			} else {

				if ($op === 'LIKE_') {
					$value = str_replace('%', '\%', $this->value);
					$op = 'LIKE';
				} else {
					$value = str_replace(['%', '_'], ['\%', '\_'], $this->value);
				}

				if ($op === 'LIKE%') {
					$value = $value . '%';
					$op = 'LIKE';
				}

				if ($op === 'LIKE%%') {
					$value = '%' . $value . '%';
					$op = 'LIKE';
				}

				$value = $this->db->escape($value);
			}


			$result = $column . ' ' . $op . " " . $value;
		}

		return $result;
	}
}