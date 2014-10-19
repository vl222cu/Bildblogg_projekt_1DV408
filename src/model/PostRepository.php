<?php

namespace model;

require_once ('./src/model/Repository.php');

class PostRepository extends base\Repository {

	private static $strImage = "image";
	private static $strComment = "comment";
	private static $dateAdded = "dateAdded";
	private $imageFolder = "./images/";


	 public function __construct() {

    	$this->dbTable = "photos";
    }

	public function saveImage($img, $comment) {

		//Katalog i servern där bilderna sparas
		$targetImg = "./images/";
		$targetImg = $targetImg . basename($img);
		$targetImg = explode(".", $targetImg);

		/*Använder tid som namn för bildfilen för att förhindra
		att bilder med samma namn skrivs över*/
		$targetImg = time().'.'.array_pop($targetImg);

		//Kontrollerar först om bildfilen sparas till servern innan den sparas i DB
		if (move_uploaded_file($_FILES['file']['tmp_name'], $this->imageFolder . $targetImg)) {
			//Sparar informationen i databasen
			$db = $this->connection();

	    	$sql = "INSERT INTO $this->dbTable (" . self::$strImage . ", " . self::$strComment . ") VALUES (?, ?)";
	    	$query = $db->prepare($sql);
	    	$params = array($targetImg, $comment);
	    	$statement = $query->execute($params); 

	    	//Stänger PDO-uppkopplingen till databasen
	        $this->db = null;

	        if ($statement) {

	        	return true;

	        } else {

	        	return false;
	        }

    	} else {

    		return false;
    	}
	}

	public function getAllImagesFromDB() {

		$db = $this->connection();
		$dateImages = array();

		$sql = "SELECT * FROM $this->dbTable ORDER BY dateAdded DESC, imgID DESC";
		$query = $db -> prepare($sql);
		$query -> execute();

		while ($result = $query->fetch(\PDO::FETCH_ASSOC)) {

			$dateImages[$result[self::$dateAdded]][] = $result;

		}

		return $dateImages;
	}
}