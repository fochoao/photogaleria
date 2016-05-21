<?php
	class mysql_class {
		public $db;
		public $host;
		public $user;
		public $password;
		private $connection;
		public $type;
		public $query_str;
		public $variables;
		public $pdo_connection;
		public $filter_var;
		private $filtered_string;
		public function connection()
		{
			try
			{
				$this->connection = new PDO('mysql:host='.$this->host.';', $this->user, $this->password);
			} catch (PDOException $error)
			{
				return $error->getMessage();
			}
			return $this->connection;
		}
		public function query()
		{
			$query_type = $this->type;
			$query_string = $this->query_str;
			$query_variables = $this->variables;
			$query_connection = $this->pdo_connection;
			if ($query_type == "selectone")
			{
				$result = $query_connection->prepare($query_string);
				$result->execute($query_variables);
				$result = $result->fetch();
			} else if ($query_type == "selectonenovar")
			{
				$result = $query_connection->prepare($query_string);
				$result->execute();
				$result = $result->fetch();
			} else if ($query_type == "selectmany")
			{
				$result = $query_connection->prepare($query_string);
				$result->execute($query_variables);
				$result = $result->fetchAll();
			} else if ($query_type == "selectmanynovar")
			{
				$result = $query_connection->prepare($query_string);
				$result->execute();
				$result = $result->fetchAll();
			} else if ($query_type == "other")
			{
				$result = $query_connection->prepare($query_string);
				$result->execute($query_variables);
			} else if ($query_type == "othernovar")
			{
				$result = $query_connection->prepare($query_string);
				$result->execute();
			}
			return $result;
		}
		public function filter_string_xss()
		{
			$this->filtered_string = strip_tags($this->filter_var);
			$this->filtered_string = stripslashes($this->filtered_string);
			$this->filtered_string = stripcslashes($this->filtered_string);
			return $this->filtered_string;
		}
	}
?>