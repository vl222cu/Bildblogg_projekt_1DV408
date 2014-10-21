<?php

namespace model;

require_once ('./src/model/Repository.php');
require_once ('./src/model/PostModel.php');

class PostRepository extends base\Repository {

	private static $postId = "imgID";
	private static $strImage = "image";
	private static $strComment = "comment";
	private static $dateAdded = "dateAdded";
	private static $imgFile = "file";
	private static $tempFile = "tmp_file";
	private $imageFolder = "./images/";
	private $postModel;


	 public function __construct() {

    	$this->dbTable = "photos";
    	$this->postModel = new postModel();

    }

	public function saveImage($img, $comment) {

		//Katalog i servern där bilderna sparas
		$targetImg = $this->imageFolder;
		$targetImg = $targetImg . basename($img);
		$targetImg = explode(".", $targetImg);

		/*Använder tid som namn för bildfilen för att förhindra
		att bilder med samma namn skrivs över*/
		$targetImg = time().'.'.array_pop($targetImg);

		//Kontrollerar först om bildfilen sparas till servern innan den sparas i DB
		if (move_uploaded_file(\model\PostModel::$imgInfo, $this->imageFolder . $targetImg)) {
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
		//Stänger PDO-uppkopplingen till databasen
		$this->db = null;

		return $dateImages;
	}

	public function deletePostFromDB($displayedImg) {

		if (@unlink($this->imageFolder . $displayedImg)) {

			$db = $this->connection();

			$sql = "DELETE FROM $this->dbTable WHERE " . self::$strImage . " = ?";
			$query = $db->prepare($sql);
			$params = array($displayedImg);
			$statement = $query->execute($params); 

			//Stänger PDO-uppkopplingen till databasen
		    $this->db = null;

			return true;

		} else {

			return false;
		}
	}

	public function getSelectedPostToEdit($imgID) {

		$db = $this->connection();
		$selectedPost = array();

		$sql = "SELECT * FROM $this->dbTable WHERE " . self::$postId . " = ?";
		$query = $db->prepare($sql);
		$params = array($imgID);
		$query->execute($params); 

		while ($result = $query->fetch(\PDO::FETCH_ASSOC)) {

			array_push($selectedPost, $result);

		}

		//Stänger PDO-uppkopplingen till databasen
		$this->db = null;

		return $selectedPost;

	}

	public function editSelectedPost($imgID, $selectedImg, $selectedComment) {

		$oldPost = $this->getSelectedPostToEdit($imgID);
		$oldPost = $oldPost[0];
		$oldImg = $oldPost[self::$strImage];
		var_dump($oldImg);

		if ($oldImg !== $selectedImg) {

			@unlink($this->imageFolder . $old_Imgfile);
		}
	
		//Katalog i servern där bilderna sparas
		$targetImg = $this->imageFolder;
		$targetImg = $targetImg . basename($selectedImg);
		$targetImg = explode(".", $targetImg);

		/*Använder tid som namn för bildfilen för att förhindra
		att bilder med samma namn skrivs över*/
		$targetImg = time().'.'.array_pop($targetImg);

		//Kontrollerar först om bildfilen sparas till servern innan den sparas i DB
		if (move_uploaded_file(\model\PostModel::$imgInfo, $this->imageFolder . $targetImg)) {
			//Sparar informationen i databasen
			$db = $this->connection();

			$sql = "UPDATE $this->dbTable SET image = ?, comment = ? WHERE imgID = ?";
			$query = $db->prepare($sql);
			$params = array($imgID, $targetImg, $comment);
			$statement = $query->execute($params); 

			//Stänger PDO-uppkopplingen till databasen
			$this->db = null;

			if ($statement) {

			    return true;

			} else {

			    return false;
			}
		}
	}

	public function editSelectedComment($imgID, $comment) {
		var_dump($imgID);
		$db = $this->connection();

		$sql = "UPDATE $this->dbTable SET comment = ? WHERE imgID = ?";
		$query = $db->prepare($sql);
		$params = array($comment, $imgID);
		$query->execute($params); 
		$affected_row = $query->rowCount();
			//Stänger PDO-uppkopplingen till databasen
		$this->db = null;

		if ($affected_row != "") {

			return true;

		} else {

			return false;
		}
	}
}