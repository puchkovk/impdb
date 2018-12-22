<?php
/**
 * Date: 22.12.2018
 */

namespace ImpDB\Driver;

use \ImpDB\Query\Expr;
use \ImpDB\Exception\DbException;
use \ImpDB\Driver\MySQL\Result;

class MySQL extends Driver implements DriverInterface
{
	/**
	 * @var \mysqli
	 */
	protected $connection;

	/**
	 * @return mixed
	 */
	public function insertId()
	{
		return $this->connection->insert_id;
	}

	public function query(string $query)
	{
		$dbResult = $this->connection->query($query);

		return new Result($dbResult);
	}

	public function esc($value, bool $doQuote = true): string
	{
		$result = NULL;

		if ($value instanceof Expr) {
			$result = $value->value();
		} else {
			if ( !($this->connection instanceof \mysqli)) {
				throw new DbException('Database connection required!');
			}
			$result = $this->connection->real_escape_string($value);
		}

		return $doQuote ? "'".$result."'" : $result;
	}

	/**
	 *
	 */
	public function affectedRows():int
	{
		return $this->connection->affected_rows;
	}

	public function __construct(string $user, string $password, string $database, string $host = 'localhost', int $port = 3306)
	{
		$this->connection = new \mysqli($host, $user, $password, $database, $port);
		//restore_error_handler();

		if ($this->connection->connect_errno) {
			throw new DbException($this->connection->connect_error, $this->connection->connect_errno);
		}

		$this->connection->query('SET NAMES \'utf8\'');
		$this->connection->query('SET CHARACTER SET utf8');
	}
}