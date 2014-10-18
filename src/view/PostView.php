<?php

namespace view;

class PostView {

	private $postModel;
	private $message = '';
	private $strComment = 'comment';
	public static $actionUpload = 'upload';
	public static $actionUploadPage = 'uploadpage';
	public static $actionReturn = 'return';


	const MESSAGE_ERROR_UPLOAD_SUCCESSED = 'Uppladdningen lyckades';
	const MESSAGE_ERROR_UPLOAD_FAILED = 'Uppladdningen misslyckades. Kontrollera att bilden är av format jpg, gif eller png och ej större än 2MB.';


	public function __construct(\model\PostModel $postModel) {
		
		$this->postModel = $postModel;
	}

	public function mainPageHTML() {

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

	}

	public function uploadPageHTML() {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
		 			<p><a href='?return'>Tillbaka</a></p>
					<div id='formwrapper'>
				 		<form action='?upload' method='post' enctype='multipart/form-data'>
							Välj bild och skriv gärna en kommentar: <input type='file' name='file' id='file' />  
							<input type='text' name='comment' id='comment' placeholder='Lägg till kommentar' /> 
							<input type='submit' name='submit' id='uploadButton' value='Ladda upp' />
						</form>
					</div>
				</div>
		 	</div>";

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

    public function getComment() {

    	if(isset($_POST[$this->strComment])){

	        return  $_POST[$this->strComment];

	    }
    }
}