<?php
/**
 * Copyright © 2018 Konstantin Puchkov. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 * File: /ImpDB/Query/Conditions.php
 */

namespace ImpDB\Query;

use ImpDB\Exception\QueryException;

class Conditions extends Builder
{
	/**
	 * @var Join[] $join
	 */
	protected $join = [];

	/**
	 * @var Join $join
	 */
	protected $last_join = null;

	/**
	 * @var Where[] $where
	 */
	protected $where = [];
	protected $groupBy = [];
	protected $orderBy = [];
	protected $limit;
	protected $offset;

	public function join($table, $type = null)
	{
		if (!is_null($this->last_join)) {
			$this->join[] = $this->last_join;
		}

		$this->last_join = new Join($this->db);
		$this->last_join->table($table);
		if (!empty($type)) {
			$this->last_join->type($type);
		}

		return $this;
	}

	public function on($column, $op, $column2)
	{
		if (!($this->last_join instanceof Join)) {
			throw new QueryException('No join found for ON statement', 101);
		}

		$this->last_join->on($column, $op, $column2);

		$this->join[] = $this->last_join;

		return $this;
	}

	public function whereOpen()
	{
		$where = new Where($this->db);
		$this->where[] = $where->bracketOpen();

		return $this;
	}

	public function whereClose()
	{
		$where = new Where($this->db);
		$this->where[] = $where->bracketClose();

		return $this;
	}

	public function orWhereOpen()
	{
		$where = new Where($this->db);
		$this->where[] = $where->orBracketOpen();

		return $this;
	}

	public function where($column, $op, $value)
	{
		$where = new Where($this->db);
		$this->where[] = $where->column($column)->op($op)->value($value);

		return $this;
	}

	public function orWhere($column, $op, $value)
	{
		$where = new Where($this->db);
		$this->where[] = $where->column($column)->op($op)->value($value)->orWhere();

		return $this;
	}

	public function limit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	public function offset($offset)
	{
		$this->offset = $offset;

		return $this;
	}

	public function groupBy($column)
	{
		if ($column instanceof Expr) {
			$column = $column->value();
		} else {
			$column = $this->db->prepareName($column);
		}

		$this->groupBy[] = $column;

		return $this;
	}

	public function orderBy($column, $direction)
	{
		if ($column instanceof Expr) {
			$column = $column->value();
		} else {
			$column = $this->db->prepareName($column);
		}

		$direction = strtoupper($direction);

		$this->orderBy[$column] = $direction;

		return $this;
	}

	protected function build()
	{
		foreach ($this->join as $join) {
			$this->chunks[] = $join->asString();
		}


		if (!empty($this->where)) {
			$this->chunks[] = 'WHERE';
			$first = true;
			$bracketOpen = false;

			foreach ($this->where as $condition) {
				if ($condition instanceof Where) {

					$bracketClose = false;
					if ($condition->isBracketClose()) {
						$bracketClose = true;
					}
					if (!($first || $bracketOpen || $bracketClose)) {
						if ($condition->isOr()) {
							$this->chunks[] = 'OR';
						} else {
							$this->chunks[] = 'AND';
						}
					} else {
						$bracketOpen = false;
					}
					$this->chunks[] = $condition->build();
					if ($condition->isBracketOpen()) {
						$bracketOpen = true;
					}

				}
				$first = false;
			}
		}

		if (!empty($this->groupBy)) {
			$this->chunks[] = 'GROUP BY';
			$groupBy = [];
			foreach ($this->groupBy as $column) {
				$groupBy[] = $column;
			}
			$this->chunks[] = implode(', ', $groupBy);

		}
		if (!empty($this->orderBy)) {
			$this->chunks[] = 'ORDER BY';
			$orderBy = [];
			foreach ($this->orderBy as $column => $direction) {
				$orderBy[] = $column . ' ' . ($direction === 'DESC' ? 'DESC' : 'ASC');
			}
			$this->chunks[] = implode(', ', $orderBy);
		}

		if (isset($this->limit)) {
			$this->chunks[] = 'LIMIT ' . intval($this->limit);
		}

		if (isset($this->offset)) {
			$this->chunks[] = 'OFFSET ' . intval($this->offset);
		}

		parent::build();
	}
}