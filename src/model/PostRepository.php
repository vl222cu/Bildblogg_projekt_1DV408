<?php

namespace model;

require_once ('./src/model/Repository.php');

class PostRepository extends base\Repository {

	private static $strImage = "image";
	private static $strComment = "comment";
	private static $dateStamp = "dateAdded";
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

		//Sparar informationen i databasen
		$db = $this->connection();

    	$sql = "INSERT INTO $this->dbTable (" . self::$strImage . ", " . self::$strComment . ") VALUES (?, ?)";
    	$query = $db->prepare($sql);
    	$params = array($targetImg, $comment);
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