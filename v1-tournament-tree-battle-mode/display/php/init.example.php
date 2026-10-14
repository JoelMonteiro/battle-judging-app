<?php

	class Init {

		private $db;

		function __construct() {

			$this->db = new mysqli("DB_SERVER","DB_USERNAME","DB_PASSWORD","DB_NAME");

			if (mysqli_connect_errno()) 
				echo ('Não foi possível ligar à base de dados [' . mysqli_connect_err() . ']');

		}

		function connection() {

			return $this->db;

		}

	}
	
?>
