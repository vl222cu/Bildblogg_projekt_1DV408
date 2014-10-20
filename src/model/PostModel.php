<?php

namespace model;

class PostModel {

	public static $imgInfo;

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
}

