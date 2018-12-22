<?php
/**
 * Date: 23.12.2018
 */

namespace ImpDB\Driver\MySQL;


class Result
{
	/**
	 * @var \mysqli_result
	 */
	protected $dbResult;

	public function __construct(\mysqli_result $dbResult)
	{
		$this->dbResult = $dbResult;
	}

	public function fetchRowAssoc()
	{
		return $this->dbResult->fetch_assoc();
	}

	public function fetchAllAssoc()
	{
		return $this->dbResult->fetch_all(MYSQLI_ASSOC);
	}

	public function fetchAllNum()
	{
		return $this->dbResult->fetch_all(MYSQLI_NUM);
	}

	/**
	 * Synonym for mysqli::data_seek
	 * @param int $offset
	 */
	public function dataSeek(int $offset)
	{
		$this->dbResult->data_seek($offset);
	}
}