<?php

/** 
 * Search and replace text on an entire mysql database
 * 
 * @author Jon Segador <jonseg@gmail.com> || http://jonsegador.com
 * https://github.com/jonseg/mysql-search-replace
 * 
 */

class mysqlSearchAndReplace
{

	var $database;
	var $server;
	var $user;
	var $password;

	function __construct($database = "", $server = "localhost", $user = "root", $password = ""){
		$this->database = $database;
		$this->server = $server;
		$this->user = $user;
		$this->password = $password;

		if(!($conn_id = mysql_connect($server, $user, $password))){
			echo "Connection failed";
			exit;
		}

		if(!@mysql_select_db($this->database, $conn_id)){
			echo "Impossible to open ".$this->database;
			exit;
		}

	}


	function searchAndReplace($search, $replace, $casesensitive = false, $tables = array()){

		if(count($tables) == 0){
			$tables = $this->getTables();
		}

		$replacements = 0;

		foreach ($tables as $table)
		{
			$flag = 0;
			$fields = $this->columns($table);
			$fields_names = $this->columns($table, true);
		
			$where = implode('` LIKE "%'.$search.'%" OR `', $fields_names);
			$sql = "SELECT * FROM ".$table.' WHERE `'.$where.'` LIKE "%'.$search.'%"';
		
			$query = @mysql_query($sql);
			$total = mysql_num_rows($query);

			if ($total > 0)
			{
				$rows = array();
				while ($row = mysql_fetch_array($query, MYSQL_ASSOC)){
					$rows[] = $row;
				}

				foreach($rows as $r)
				{
					foreach ($fields as $field)
					{
						$field_name = $field['Field'];
						$field_value = $r[$field_name];
					
						if(!$casesensitive)
						{
							$field_value = strtolower($field_value);
							$search = strtolower($search);
						}
					
						if(strpos($field_value,$search) > 0 || $field_value == $search)
						{
						
							foreach ($fields as $field)
							{
								if($field['Key']=="PRI")
								{
									$field_key = $field['Field'];
									break;
								}
							}
						
							if($casesensitive){
								$field_value = str_replace($search,$replace,$r[$field_name]);
							}
							else{
								$field_value = str_ireplace($search,$replace,$r[$field_name]);
							}
						
							$field_value = str_replace("'", '"', $field_value);
							$update = "UPDATE ".$table." SET `".$field_name.'` = \''.$field_value.'\' WHERE `'.$field_key.'` = \''.$r[$field_key]."'";

							@mysql_query($update);

							$replacements++;
						}
					}
				}
			}
		
		}

		if($replacements == 0){
			echo "0 replacements" . "\n";
		}
		else{
			echo $replacements . " replacements" . "\n";
		}

	}


	function getTables(){
	
		$id = mysql_list_tables($this->database);
	
		$response = array();
	
		while ($row = mysql_fetch_row($id)){
			$response[] = $row[0];
		}
	
		return $response;
	}


	function columns($table, $names = false){

		$id = mysql_query("SHOW COLUMNS FROM ".$table);
	
		$response = array();
	
		while ($row = mysql_fetch_assoc($id))
		{
			if($names){
				$response[] = $row['Field'];
			}
			else{
				$response[] = $row;
			}
		}
	
		return $response;
	}


}	



// Create an instace of the class
// $mysqlSearchAndReplace = new mysqlSearchAndReplace("myDatabase", "localhost", "root", "");

// Simple search and replace
// $mysqlSearchAndReplace->searchAndReplace("old text", "new text");

// Case sensitive = true
// $mysqlSearchAndReplace->searchAndReplace("Old Text", "New text", true);

// We specify the tables where search
// $mysqlSearchAndReplace->searchAndReplace("old text", "new text", true, array('tableOne', 'tableTwo'));




?>
