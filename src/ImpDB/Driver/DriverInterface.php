<?php
/**
 * Date: 22.12.2018
 */

namespace ImpDB\Driver;


interface DriverInterface
{
	public function __construct(string $user, string $password, string $database, string $host, int $port);

	public function esc($value):string;
	public function escName(string $name):string;

	public function query(string $query);

	public function insertId();
	public function affectedRows();


	public function getLastError();
	public function getLastErrNo();
}