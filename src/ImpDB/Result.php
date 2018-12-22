<?php
/**
 * Copyright © 2018. Konstantin Puchkov. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 * File: /ImpDB/Result.php
 */

namespace ImpDB;


class Result
{
	protected $type;

	/**
	 * @var \mysqli
	 */
	protected $db;

	/**
	 * @var \mysqli_result
	 */
	protected $dbResult;
	protected $columnIndex;
	protected $columnValue;

	/**
	 * Result constructor.
	 * @param string $type
	 * @param \mysqli_result $dbResult
	 * @param \mysqli $db
	 */
	public function __construct($type, $dbResult, $db)
	{
		$this->type = $type;
		$this->dbResult = $dbResult;
		$this->db = $db;
	}

	/**
	 * Вернет значение AUTO INCREMENT поля последней вставленной записи
	 * @return mixed
	 */
	public function getLastInsertId()
	{
		return $this->db->insert_id;
	}

	public function fetchAssoc($keyColumn = null, $valueColumn = null, $mapping = null)
	{
		$data = [];
		while ($row = $this->fetchRowAssoc($mapping)) {
			if (!empty($valueColumn) && isset($row[$valueColumn])) {
				$value = $row[$valueColumn];
			} else {
				$value = $row;
			}
			if (!empty($keyColumn) && isset($row[$keyColumn])) {
				$index = $row[$keyColumn];
				$data[$index] = $value;
			} else {
				$data[] = $value;
			}
		}

		return $data;
	}

	/**
	 * @param null $mapping
	 * @return array|null
	 */
	public function fetchRow($mapping = null)
	{
		return $this->fetchRowAssoc($mapping);
	}

	public function fetchRowAssoc($mapping = null)
	{
		$row = $this->fetchRawRow();

		if (is_callable($mapping)) {
			$row = $mapping($row);
		}

		if (!empty($this->columnValue)) {
			$row = isset($row[$this->columnValue]) ? $row[$this->columnValue] : null;
		}

		return $row;
	}

	public function fetchRawRow()
	{
		return $this->dbResult->fetchRowAssoc();
	}
}