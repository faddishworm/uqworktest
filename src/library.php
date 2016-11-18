<?php

	class Library {
		public $id = 0;
		public $code = "";
		public $name = "";
		public $abbr = "";
		public $url = "";

		/* Constructor, takes JSON string and converts it
		 * to Library object */
		function Library($json) {
			$this->id = $json->id;
			$this->code = $json->code;
			$this->name = $json->name;
			$this->abbr = $json->abbr;
			$this->url = $json->url;
		}

		//Writes out to a simple file, ideally this would use a DTO
		//to push the object out to a persistant data structure like a database
		//but for this small project a file will be used and serialized data stored.
		function writeToFile() {
			$jsonStr = json_encode($this, JSON_UNESCAPED_SLASHES);
			$file = '../database.txt';
			$current = file_get_contents($file);
			$current .= $jsonStr . "\n";
			file_put_contents($file, $current);
		}

		//Static function that returns an instance of a library
		//based on the ID
		//TODO: Very inefficient to loop through a file and json_decode every line
		//      could improve this by doing a string comparison before decoding
		static function populateFromId($id) {
			$file = '../database.txt';
			$file = fopen("../database.txt", "r");
			while(!feof($file)){
				$line = fgets($file);
				$json = json_decode($line);
				if($json->id == $id) {
					return new Library($json);
				}
			}
			fclose($file);
			return null;
		}
	}
?>
