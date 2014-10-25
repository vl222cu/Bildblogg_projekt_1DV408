<?php

namespace model\base;

require_once("Settings.php");

abstract class Repository {

	protected $dbConnection;
	protected $dbTable;

	/**
	 * PDO-uppkoppling mot databasen
	 */
	protected function connection() {

		try {

			$this->dbConnection = new \PDO(\Settings::$DB_CONNECTION, \Settings::$DB_USERNAME, \Settings::$DB_PASSWORD,
				 array(\PDO::ATTR_EMULATE_PREPARES => false, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));

			return $this->dbConnection;	

		} catch (PDOException $e) {

			echo $e->getMessage();
		}			
	}
}