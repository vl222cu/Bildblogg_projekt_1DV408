<?php

namespace model;

class PostModel {

	public static $imgInfo;
	public static $clientId = "clientId";
	public static $remoteADDR = "REMOTE_ADDR";

	/** Metod som kontrollerar om den uppladdade filens innehåll
	 * är av typen gif, jpg eller png och maxstorlek 2MB
	 */
	public function isValidImage($imgType) {

		if ((($imgType == "image/gif")
		|| ($imgType == "image/jpeg")
		|| ($imgType == "image/jpg")
		|| ($imgType == "image/png"))
		&& ($imgType < 2000000)) {

			return true;

		}

		return false;
	}

	/** 
	 *	Metod som kontrollerar bildstorlek
	 */
	public function checkImageSize($imgInfo) {

		self::$imgInfo = $imgInfo;
		$imageInformation = getimagesize(self::$imgInfo);
		$imageWidth = $imageInformation[0]; 
		$imageHeight = $imageInformation[1]; 

		if($imageWidth > 800 || $imageHeight > 800) {
		 
		 	return false;
		}

		return true;
	}

	/** 
	 *	Metod som sparar användarens IP-adress
	 */
/*    public function setClientIdentifier($clientIdentifier) {

    	$_SESSION[self::$clientId] = $_SERVER[self::$remoteADDR];
    }

    /** 
	 *	Metod som kontrollerar användarens IP-adress
	 */
/*    public function getClientControl() {

    	if ($_SESSION[self::$clientId] === $_SERVER[self::$remoteADDR]) {

			return true;
		}

		return false;
	} */

	public function getTargetImgId() {

		if (isset($_SESSION['targetImgID'])) {

			return $_SESSION['targetImgID'];
		} 
	}

	public function getTargetCommentId() {

		if (isset($_SESSION['targetCommentID'])) {

			return $_SESSION['targetCommentID'];
		} 
	}
}

