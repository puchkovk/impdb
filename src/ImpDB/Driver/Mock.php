<?php
/**
 * Date: 22.12.2018
 */

namespace ImpDB\Driver;


class Mock extends Driver implements DriverInterface
{
	protected $host = 'localhost';
	protected $port = 3306;
	protected $user;
	protected $password;
	protected $database;

	public function insertId(): int
	{
		return rand(0,999);
	}

	public function query(string $query)
	{
		// TODO: Implement query() method.
	}

	public function __construct(string $user, string $password, string $database, string $host = 'localhost', int $port = 3306)
	{
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->host = $host;
		$this->port = $port;
	}

	public function esc($value, bool $doQuote = true):string {
		$result = preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $value);
		return $doQuote ? "'".$result."'" : $result;
	}

	public function affectedRows()
	{
		// TODO: Implement affectedRows() method.
		return 1;
	}
}