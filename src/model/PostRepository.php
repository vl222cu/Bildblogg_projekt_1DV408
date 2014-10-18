<?php

namespace model;

require_once ('./src/model/Repository.php');

class PostRepository extends base\Repository {

	private static $strImage = 'image';
	private static $strComment = 'comment';
	private static $dateStamp = 'dateAdded';
	private $imageFolder = "./images/";

	 public function __construct() {

    	$this->dbTable = 'photos';
    }

	public function saveImage() {

		//Katalog i servern där bilderna sparas
		$targetImg = "./images/";
		$targetImg = $target . basename( $_FILES["file"]["name"]);
		var_dump($_FILES["file"]["name"]);
		$targetImg = explode(".", $target);
		$targetImg = time().'.'.array_pop($target);

		$comment = $this->postView->getComment();

		//Sparar informationen i databasen
		$db = $this->connection();

    	$sql = "INSERT INTO $this->dbTable (" . self::$strImage . ", " . self::$strComment . ", ".self::$dateStamp.") VALUES (?, ?, ?)";
    	$query = $db->prepare($sql);
    	$params = array($targetImg, $comment, CURDATE());
    	$statement = $query->execute($params); 

    	if($statement) {

    		$this->saveImageToServer($targetImg);

            return true;

        } else {

            return false;

        } 
	}

	public function saveImageToServer($targetImg) {

		if (move_uploaded_file($_FILES['file']['tmp_name'], $this->imageFolder . $targetImg)) {
 			
 			return true;

		} else {
 
			return false;

		}
	}
}