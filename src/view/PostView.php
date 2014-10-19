<?php

namespace view;

class PostView {

	private $postModel;
	private $message = "";
	private static $strComment = "comment";
	private static $imgFile = "file";
	private static $imgName = "name";
	private static $imgType = "type";
	public static $actionUpload = "upload";
	public static $actionUploadPage = "uploadpage";
	public static $actionReturn = 'return';

	const MESSAGE_UPLOAD_SUCCESSED = "Uppladdningen lyckades";
	const MESSAGE_ERROR_UPLOAD_FAILED = "Uppladdningen misslyckades. Kontrollera att bilden är av format jpg, gif eller png och ej större än 2MB";
	const MESSAGE_ERROR_UPLOAD_TO_SERVER = "Något gick fel! Bilden kunde inte sparas";

	public function __construct(\model\PostModel $postModel) {
		
		$this->postModel = $postModel;
	}

/*	public function mainPageHTML() {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
			 		<div id='contentwrapper'>
			 		<form enctype='multipart/form-data' method='post' action='?uploadpage'>
			 			<input type='submit' name='submit' id='uploadPageButton' value='Ladda upp bild' />
			 		</form>
				 		<p>Här ska alla bilder och kommentarer finnas</p>
					</div>
				</div>
		 	</div>";

		return $html;
	} */

	public function uploadPageHTML() {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
		 			<p><a href='?return'>Tillbaka</a></p>";

		if($this->getMessage() !== null) {

			$html .= $this->message;
		};
		
		$html .= "			
			<div id='formwrapper'>
				 		<form action='?upload' method='post' enctype='multipart/form-data'>
							Välj bild och skriv gärna en kommentar: 
							<p><input type='file' name='file' id='file' /></p>  
							<p><textarea rows='4' cols='50' name='comment' id='comment' placeholder='Lägg till kommentar' /> 
							</textarea></p>
							<input type='submit' name='submit' id='uploadButton' value='Ladda upp' />
						</form>
					</div>
				</div>
		 	</div>";

		return $html;
	}

	public function showAllImagesHTML(array $dbImages) {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
			 		<div id='contentwrapper'>
			 		<form enctype='multipart/form-data' method='post' action='?uploadpage'>
			 			<input type='submit' name='submit' id='uploadPageButton' value='Posta ett inlägg' />
			 		</form>";

		foreach ($dbImages as $date => $images) {
		
			foreach ($images as $image) {

					$imageURL = $image["image"];
					$commentText = $image["comment"];

					$html .= "
						<div class='image'>
							<a title='photoblog' href='./images//$imageURL'>
							<img src='./images//$imageURL'/></a>
							<div class='commentwrapper'>
								<p>$commentText</p>
							</div>
						</div>";
			}

		}

		return $html;
	}

	public function getAction() {

		switch (key($_GET)) {

			case self::$actionUploadPage:
				$action = self::$actionUploadPage;
				return $action;
				break;

			case self::$actionUpload:
				$action = self::$actionUpload;
				return $action;
				break;

			case self::$actionReturn:
				$action = self::$actionReturn;
				return $action;
				break;

			default:
				$action = "";
		}
	}

	public function setMessage($msg) {

		$this->message = '<p>' . $msg . '</p>';
	}

	 public function getMessage() {

        return $this->message;
    }

    public function getImage() {

    	if (isset( $_FILES[self::$imgFile]) && !empty($_FILES[self::$imgFile][self::$imgName])) {

    		return $_FILES[self::$imgFile][self::$imgName];
  		}

  		return NULL; 
    }

    public function getImageType() {

    	if (isset( $_FILES[self::$imgFile][self::$imgType]) && !empty( $_FILES[self::$imgFile][self::$imgType])) {

    		return $_FILES[self::$imgFile][self::$imgType];
  		}

  		return NULL;
    }

    public function getComment() {

    	if(isset($_POST[self::$strComment])) {

	        return  $_POST[self::$strComment];
	    }

	    return NULL;
    }

    public function userHasPressedUploadImage() {

    	if(isset($_POST[self::$actionUploadPage])) {

	        return  true;
	    }

	    return false;
    }
}