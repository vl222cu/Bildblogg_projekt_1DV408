<?php

namespace view;

class PostView {

	private $postModel;
	private $message = "";
	private static $strComment = "comment";
	private static $imgFile = "file";
	private static $imgName = "name";
	private static $tempName = "tmp_name";
	private static $imgType = "type";
	private static $imageURL = "image";
	private static $updatedComment = "commentupdate";

	public static $actionUpload = "upload";
	public static $actionUploadPage = "uploadpage";
	public static $actionReturn = "return";
	public static $actionDelete = "delete";
	public static $actionUpdateCommentPage = "updatecommentpage";
	public static $actionUpdateComment = "updatecomment";
	public static $actionUpdateImagePage = "updateimagepage";
	public static $actionUpdateImage = "updateimage";

	const MESSAGE_UPLOAD_SUCCESSED = "Uppladdning av inlägg lyckades";
	const MESSAGE_ERROR_UPLOAD_FAILED = "Uppladdning av inlägg misslyckades. Kontrollera att bilden är av format jpg, gif eller png och ej större än 2MB med maxbredd 800px och maxlängd 800px";
	const MESSAGE_ERROR_UPLOAD_TO_SERVER = "Något gick fel! Det postade inlägget kunde inte sparas";
	const MESSAGE_DELETE_SUCCESSED = "Det postade inlägget är borttagen";
	const MESSAGE_DELETE_FAILED = "Något gick fel! Det postade inlägget kunde inte tas bort";
	const MESSAGE_UPDATE_COMMENT_SUCCESSED = "Kommentaren är uppdaterad";
	const MESSAGE_ERROR_UPDATE_COMMENT_FAILED = "Något gick fel! Kommentaren kunde inte uppdateras";
	const MESSAGE_UPDATE_IMAGE_SUCCESSED = "Bilden är uppdaterad";
	const MESSAGE_ERROR_UPDATE_IMAGE_TO_SERVER = "Något gick fel! Det gick inte att uppdatera bilden";
	const MESSAGE_ERROR_UPDATE_IMAGE_FAILED = "Uppdatering av inlägg misslyckades. Kontrollera att bilden är av format jpg, gif eller png och ej större än 2MB med maxbredd 800px och maxlängd 800px";
	
	public function __construct(\model\PostModel $postModel) {
		
		$this->postModel = $postModel;
	}

	/** 
	 * HTML för uppladdning av inlägg 
	 */
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
							<p><textarea rows='10' cols='80' name='comment' id='comment' placeholder='Lägg till kommentar' /></textarea></p>
							<input type='submit' name='submit' id='uploadButton' value='Ladda upp' />
						</form>
					</div>
				</div>
		 	</div>";

		return $html;
	}

	/** 
	 * HTML som visar samtliga inlägg
	 */
	public function showAllImagesHTML(array $dbImages) {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
			 		<div id='contentwrapper'>";

		if($this->getMessage() !== null) {

			$html .= $this->message;
		};

		$html .= "
			<form enctype='multipart/form-data' method='post' action='?uploadpage'>
			 	<input type='submit' name='submit' id='uploadPageButton' value='Posta nytt inlägg' />
			</form>";

		foreach ($dbImages as $date => $images) {
		
			foreach ($images as $image) {

				$postId = $image["imgID"];
				$imageURL = $image["image"];
				$commentText = $image["comment"];

				$html .= "
					<div class='image'>
						<a title='photoblog' href='./images/$imageURL'>
						<img src='./images/$imageURL'/></a>
						<div class='commentwrapper'>
							<p>$commentText</p>
						</div>
						<form action='?delete' enctype='multipart/form-data' method='post'>
							<input type='hidden' value='$imageURL' name='delete_file' />
			 				<input type='submit' name='submit' id='deleteButton' value='Radera inlägg' />
			 			</form>
			 			<form action='?updatecommentpage' enctype='multipart/form-data' method='post'>
			 				<input type='hidden' value='$postId' name='update_file' />
			 				<input type='submit' name='submit' id='updateCommentButton' value='Ändra kommentar' />
			 			</form>
			 			<form action='?updateimagepage' enctype='multipart/form-data' method='post'>
			 				<input type='hidden' value='$postId' name='update_file' />
			 				<input type='submit' name='submit' id='updateImageButton' value='Ändra bild' />
			 			</form>
					</div>";
			}

		}

		return $html;
	}

	/** 
	 * HTML för uppdatering av kommentar
	 */
	public function updateCommentPageHTML(array $selectedPost) {

		$selectedPost = $selectedPost[0];
		$postId = $selectedPost["imgID"];
		$postURL = $selectedPost["image"];
		$postCommentText = $selectedPost["comment"];

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
		 			<p><a href='?return'>Tillbaka</a></p>
			 		<div id='contentwrapper'>";

		if($this->getMessage() !== null) {

			$html .= $this->message;
		};

		$html .= "
			<div class='image'>
				<img src='./images/$postURL'/>
			</div>
			<div id='formwrapper'>
				<form action='?updatecomment' method='post' enctype='multipart/form-data'>
					Uppdatera kommentar:  
					<p><textarea rows='4' cols='80' name='commentupdate' id='commentupdate'/>$postCommentText</textarea></p>
					<input type='hidden' value='$postId' name='update_comment' />
					<input type='submit' name='submit' id='updateCommentButton' value='Uppdatera kommentar' />
				</form>
			</div>";
					
		return $html;
	}

	/** 
	 * HTML för uppdatering av bild
	 */
	public function updateImagePageHTML(array $selectedPost) {

		$selectedPost = $selectedPost[0];
		$postId = $selectedPost["imgID"];
		$postURL = $selectedPost["image"];
		$postCommentText = $selectedPost["comment"];

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
		 			<p><a href='?return'>Tillbaka</a></p>
			 		<div id='contentwrapper'>";

		if($this->getMessage() !== null) {

			$html .= $this->message;
		};

		$html .= "
			<div class='image'>
				<img src='./images/$postURL'/>
			</div>
			<div id='formwrapper'>
				<form action='?updateimage' method='post' enctype='multipart/form-data'>
					<p>$postCommentText</p>
					Uppdatera bild: 
					<p><input type='file' name='file' id='file' /></p>  
					<input type='hidden' value='$postId' name='update_image' />
					<input type='submit' name='submit' id='updateImageButton' value='Uppdatera bild' />
				</form>
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

			case self::$actionDelete:
				$action = self::$actionDelete;
				return $action;
				break;

			case self::$actionUpdateCommentPage:
				$action = self::$actionUpdateCommentPage;
				return $action;
				break;

			case self::$actionUpdateComment:
				$action = self::$actionUpdateComment;
				return $action;
				break;

			case self::$actionUpdateImagePage:
				$action = self::$actionUpdateImagePage;
				return $action;
				break;

			case self::$actionUpdateImage:
				$action = self::$actionUpdateImage;
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

    public function getTempImage() {

    	if (isset( $_FILES[self::$imgFile]) && !empty($_FILES[self::$imgFile][self::$tempName])) {

    		return $_FILES[self::$imgFile][self::$tempName];
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

    public function getUpdatedComment() {

    	if(isset($_POST[self::$updatedComment])) {

	        return  $_POST[self::$updatedComment];
	    }

	    return NULL;
    }

    public function getImageURL() {

		if (isset($_POST['delete_file'])) {

			return $_POST['delete_file'];
		}

		return NULL;
	}

	 public function getPostId() {

		if (isset($_POST['update_file'])) {

			return $_POST['update_file'];
		}

		return NULL;
	}

	public function getCommentId() {

		if (isset($_POST['update_comment'])) {

			return $_POST['update_comment'];
		}

		return NULL;
	}

	public function getImageId() {

		if (isset($_POST['update_image'])) {

			return $_POST['update_image'];
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