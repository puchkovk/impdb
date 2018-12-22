<?php
/**
 * Date: 22.12.2018
 */

namespace ImpDB\Driver;

use \ImpDB\Query\Expr;


abstract class Driver
{
	protected $connection;
	protected $error;
	protected $errno;

	/**
	 * @param mixed $value
	 * @param bool $doQuote adds quotes to return string
	 * @return string
	 */
	public function esc($value, bool $doQuote = true): string
	{
		$result = NULL;

		if ($value instanceof Expr) {
			$result = $value->value();
		} else {
			$result = preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $value);;
		}

		return $doQuote ? "'".$result."'" : $result;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function escName(string $name): string
	{
		$nameChunks = explode('.', $name);
		$nameChunks = array_map(function($chunk) {
			return $this->esc($chunk, false);
		}, $nameChunks);
		return '`'.implode('`.`', $nameChunks).'`';
	}

	public function getLastError()
	{
		return $this->error;
	}

	public function getLastErrNo()
	{
		return $this->errno;
	}
}
