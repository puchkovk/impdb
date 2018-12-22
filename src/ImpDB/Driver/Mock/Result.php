<?php
/**
 * Date: 23.12.2018
 */

namespace ImpDB\Driver\MySQL;


class Result
{
	protected $dbResult;

	public function __construct($dbResult)
	{
		$this->dbResult = $dbResult;
	}
}