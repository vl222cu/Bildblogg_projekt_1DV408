<?php

namespace model;

class PostModel {

	/*** Metod som kontrollerar om den uppladdade filens innehåll
	är av typen gif, jpg eller png och maxstorlek 2MB ***/
	public function isValidImage() {

		if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < 2000000)) {

			return true;

		}

		return false;
	}
}

