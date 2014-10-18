<?php

namespace model;

class PostModel {

	/*** Metod som kontrollerar om den uppladdade filens innehåll
	är av typen gif, jpg eller png och maxstorlek 2MB ***/
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
}

