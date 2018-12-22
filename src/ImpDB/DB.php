<?php
/**
 * /ImpDB/DB.php
 *
 * Copyright © 2018 Konstantin Puchkov. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 */

namespace ImpDB;

use ImpDB\Driver\DriverInterface;
use ImpDB\Exception\DbException;
use ImpDB\Exception\QueryException;
use ImpDB\Query\Delete;
use ImpDB\Query\Expr;
use ImpDB\Query\Insert;
use ImpDB\Query\Select;
use ImpDB\Query\Update;
use ImpDB\Mock\MysqliMock;

class DB
{
	const ESCAPE_ALLOW_ASTERISK = 1 << 0;
	const ESCAPE_ALLOW_ALIAS = 1 << 1;

	const TYPE_SELECT = 'select';
	const TYPE_INSERT = 'insert';
	const TYPE_UPDATE = 'update';
	const TYPE_DELETE = 'delete';
	const TYPE_RAW    = 'raw';

	/**
	 * @var \mysqli
	 */
	protected $driver;
	protected $host;
	protected $user;
	protected $password;
	protected $port;
	protected $database_name;
	protected $lastQuery;
	protected $logQueries = false;

	public function __construct(DriverInterface $driver)
	{
		if ( !empty($driver)) {
			$this->driver = $driver;
		} else {
			throw new DbException('Missing DB driver');
		}
	}

	/**
	 * Send raw query to database
	 *
	 * @param string $type
	 * @param string $query
	 * @return Result
	 * @throws DbException
	 */
	public function query($type, $query)
	{
		if ($this->logQueries) {
			$this->lastQuery = $query;
		}

		$r = $this->driver->query($query);

		if ($this->driver->getLastErrNo()) {
			throw new DbException($this->driver->getLastError(), $this->driver->getLastErrNo());
		}

		$result = new Result($type, $r, $this->driver);

		return $result;
	}

	public function select(array $fields = [])
	{
		$query = new Select($this);
		if (!empty($fields)) {
			$query->columns($fields);
		}
		return $query;
	}

	public function insert($table)
	{
		$query = new Insert($this);
		$query->into($table);

		return $query;
	}

	public function update($table)
	{
		$query = new Update($this);
		$query->table($table);
		return $query;
	}

	/**
	 * @param $table
	 * @return Delete
	 */
	public function delete($table)
	{
		$query = new Delete($this);
		$query->table($table);
		return $query;
	}

	/**
	 * @param mixed $value
	 * @param bool $doQuote
	 * @return string
	 * @throws DbException
	 */
	public function escape($value, $doQuote = true)
	{
		$result = NULL;

		if ($value instanceof Expr) {
			$result = $value->value();
		} else {
			$result = $this->driver->esc($value, $doQuote);
		}

		return $result;
	}

	/**
	 * @param mixed $column
	 * @param int
	 * @return null|string
	 * @throws QueryException
	 * @throws DbException
	 */
	public function prepareName($column, $params = 0)
	{
		if ($column instanceof Expr) {
			$column = $column->value();
		} else {
			$alias = false;
			if (is_array($column)) {
				if ($params & self::ESCAPE_ALLOW_ALIAS) {
					list($column, $alias) = $column;
					$alias = $this->driver->escName($alias);
				} else {
					throw new QueryException('Unallowed alias', 100); // \('Database: unallowed alias.');
				}
			}

			if (false !== strpos($column, '.')) {
				list($table, $column) = explode('.', $column);
				$table = $this->driver->escName($table);
			}
			if (!($params & self::ESCAPE_ALLOW_ASTERISK && $column === '*')) {
				$column = $this->driver->escName($column);
			}

			if (!empty($table)) {
				$column = $table . '.' . $column;
			}

			$column .= ($alias ? (' as ' . $alias) : '');
		}

		return $column;

	}

	public function getLastQuery()
	{
		return $this->lastQuery;
	}

	public function logQueries($doLog)
	{
		$this->logQueries = $doLog;
	}

	/**
	 * @param $value
	 * @return \ImpDB\Query\Expr
	 */
	public function expr($value)
	{
		return new Expr($value);
	}
}