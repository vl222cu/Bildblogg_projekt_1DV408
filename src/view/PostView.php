<?php

namespace view;

class PostView {

	private $postModel;
	private $name;
	private $commentText;
	private $postedByName;
	private $message = "";
	private static $userName = 'Username';
	private static $password = 'Password';
	private static $strComment = "comment";
	private static $strPostedBy = "postedby";
	private static $imgFile = "file";
	private static $imgName = "name";
	private static $tempName = "tmp_name";
	private static $imgType = "type";
	private static $imageURL = "image";
	private static $updatedComment = "commentupdate";
	private static $deleteFile = "delete_file";
	private static $updateFile = "update_file";
	private static $commentId = "update_comment";
	private static $imageId = "update_image";

	public static $actionLoginPage = "loginpage";
	public static $actionLogin = "login";
	public static $actionLogout = "logout";
	public static $actionUpload = "upload";
	public static $actionUploadPage = "uploadpage";
	public static $actionReturn = "return";
	public static $actionDelete = "delete";
	public static $actionUpdateCommentPage = "updatecommentpage";
	public static $actionUpdateComment = "updatecomment";
	public static $actionUpdateImagePage = "updateimagepage";
	public static $actionUpdateImage = "updateimage";

	const MESSAGE_ERROR_USERNAME_PASSWORD = 'Felaktigt användarnamn och/eller lösenord';
	const MESSAGE_ERROR_USERNAME = 'Användarnamn saknas';
	const MESSAGE_ERROR_PASSWORD = 'Lösenord saknas';
	const MESSAGE_SUCCESS_LOGIN = 'Inloggning lyckades';
	const MESSAGE_SUCCESS_LOGOUT = 'Utloggningen lyckades';
	const MESSAGE_UPLOAD_SUCCESSED = "Uppladdning av inlägg lyckades";
	const MESSAGE_ERROR_UPLOAD_FAILED = "Uppladdning av inlägg misslyckades. Kontrollera att bilden är av format jpg/jpeg, gif eller png och ej större än 2MB med maxbredd 800px och maxlängd 800px";
	const MESSAGE_ERROR_UPLOAD_TO_SERVER = "Något gick fel! Det postade inlägget kunde inte sparas";
	const MESSAGE_DELETE_SUCCESSED = "Det postade inlägget är borttaget";
	const MESSAGE_DELETE_FAILED = "Något gick fel! Det postade inlägget kunde inte tas bort";
	const MESSAGE_UPDATE_COMMENT_SUCCESSED = "Kommentaren är uppdaterad";
	const MESSAGE_ERROR_UPDATE_COMMENT_FAILED = "Något gick fel! Kommentaren kunde inte uppdateras";
	const MESSAGE_UPDATE_IMAGE_SUCCESSED = "Bilden är uppdaterad";
	const MESSAGE_ERROR_UPDATE_IMAGE_TO_SERVER = "Något gick fel! Det gick inte att uppdatera bilden";
	const MESSAGE_ERROR_UPDATE_IMAGE_FAILED = "Uppdatering av inlägg misslyckades. Kontrollera att bilden är av format jpg, gif eller png och ej större än 2MB med maxbredd 800px och maxlängd 800px";

	public function __construct(\model\PostModel $postModel) {
		
		$this->postModel = $postModel;
		$this->name = isset($_POST[self::$userName]) ? $_POST[self::$userName] : '';
		$this->commentText = isset($_POST[self::$strComment]) ? $_POST[self::$strComment] : '';
		$this->postedByName = isset($_POST[self::$strPostedBy]) ? $_POST[self::$strPostedBy] : '';

	}

	public function showLoginPageHTML() {

		$html = "
			<div id='maincontainer'>
				 <h1>Vivis bildblogg</h1>
				 <p><a href='?return' class='return'>Tillbaka</a></p>
				 <form name='login' method='post' accept-charset='utf-8' action='?login'>			
					<div id='loginwrapper'>
					<p>Login - Skriv in användarnamn och lösenord</p>";

		if($this->getMessage() !== null) {

			$html .= "<div class='loginmsg'>$this->message</div>";
		};

	    $html .= "
						<p><label for='username'>Användarnamn</label>
						<input type='username' name='Username' id='nameinput' value='$this->name'></p>
						<p><label for='password'>Lösenord</label>
						<input type='password' name='Password' id='passwordinput'></p>
						</div>
						<p><input type='submit' name='submit' id='loginButton' value='Logga in'></p>			
					</form>
				</div>
			";

			return $html;
	}

	/** 
	 * HTML för uppladdning av inlägg 
	 */
	public function uploadPageHTML() {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
		 			<p><a href='?return' class='return'>Tillbaka</a></p>
		 			<div id='uploadwrapper'>";

		if($this->getMessage() !== null) {

			$html .= "<div class=Msgstatus>$this->message</div>";
		};
		
		$html .= "			
						<div id='formwrapper'>
					 		<form action='?upload' method='post' enctype='multipart/form-data'>
								Välj bild och skriv gärna en kommentar: 
								<p><input type='file' name='file' id='file' /></p>  
								<p><textarea rows='10' cols='80' name='comment' id='comment' placeholder='Lägg till kommentar' />$this->commentText</textarea></p>
								<p><input type='text' name='postedby' id='postedby' value='$this->postedByName' placeholder='Fyll i ditt namn här' /></p>
								<input type='submit' name='submit' id='uploadButton' value='Ladda upp' />
							</form>
						</div>
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

			$html .= "<div class=Msgstatus>$this->message</div>";
		};

		$html .= "
			<p><a href='?loginpage'>Logga in</a></p>
			<form enctype='multipart/form-data' method='post' action='?uploadpage'>
			 	<input type='submit' name='submit' class='uploadPageButton' value='Posta nytt inlägg'>
			</form>";

		foreach ($dbImages as $date => $images) {
		
			foreach ($images as $image) {

				$postId = $image["imgID"];
				$postedBy = strip_tags($image["name"]);
				$imageURL = $image["image"];
				$commentText = strip_tags($image["comment"]);

				$html .= "
								<div class='image'>
									<a title='photoblog' href='./images/$imageURL'>
									<img src='./images/$imageURL' alt='blogpost'/></a>
									<div class='commentwrapper'>
										<p>$commentText</p>
										<p>Inlägget är postat av: $postedBy</p>
									</div>
								</div>";

			}
		}

		$html .= "
							</div>
						</div>
					</div>
				";

		return $html;
	}

	/** 
	 * HTML som visar samtliga inlägg
	 */
	public function showAllImagesWithCRUDHTML(array $dbImages) {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
			 		<div id='contentwrapper'>";

		if($this->getMessage() !== null) {

			$html .= "<div class=Msgstatus>$this->message</div>";
		};

		$html .= "
			<p><a href='?logout'>Logga ut</a></p>
			<form enctype='multipart/form-data' method='post' action='?uploadpage'>
			 	<input type='submit' name='submit' class='uploadPageButton' value='Posta nytt inlägg' />
			</form>";

		foreach ($dbImages as $date => $images) {
		
			foreach ($images as $image) {

				$postId = $image["imgID"];
				$postedBy = strip_tags($image["name"]);
				$imageURL = $image["image"];
				$commentText = strip_tags($image["comment"]);

				$html .= "
					<div class='image'>
						<a title='photoblog' href='./images/$imageURL'>
						<img src='./images/$imageURL' alt='blogpost'/></a>
						<div class='commentwrapper'>
							<p>$commentText</p>
							<p>Inlägget är postat av: $postedBy</p>
						</div>
						<table id='buttontable'>
							<tr>
								<td>
									<form action='?delete' enctype='multipart/form-data' method='post'>
										<input type='hidden' value='$imageURL' name='delete_file'>
						 				<input type='submit' name='submit' id='deleteButton' value='Radera inlägg'>
						 			</form>
					 			</td>
					 			<td>
						 			<form action='?updatecommentpage' enctype='multipart/form-data' method='post'>
						 				<input type='hidden' value='$postId' name='update_file'>
						 				<input type='submit' name='submit' id='updateCommentButton' value='Ändra kommentar'>
						 			</form>
					 			</td>
					 			<td>					 			
						 			<form action='?updateimagepage' enctype='multipart/form-data' method='post'>
						 				<input type='hidden' value='$postId' name='update_file'>
						 				<input type='submit' name='submit' id='updateImageButton' value='Ändra bild'>
						 			</form>
					 			</td>					 			
					 		</tr>
			 			</table>
					</div>";

			}
		}

		$html .= "
							</div>
						</div>
					</div>
				";

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
		 			<p><a href='?return' class='return'>Tillbaka</a></p>
			 		<div id='contentwrapper'>";

		if($this->getMessage() !== null) {

			$html .= "<div class=Msgstatus>$this->message</div>";
		};

		$html .= "
			<div class='image'>
				<img src='./images/$postURL'/>
			</div>
			<div id='formwrapper'>
				<form action='?updatecomment' method='post' enctype='multipart/form-data'>
					Uppdatera kommentar:  
					<p><textarea rows='4' cols='80' name='commentupdate' id='commentupdate'/>$postCommentText</textarea></p>
					<input type='hidden' value='$postId' name='update_comment'>
					<input type='submit' name='submit' id='updateCommentButton' value='Uppdatera kommentar'>
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
		 			<p><a href='?return' class='return'>Tillbaka</a></p>
			 		<div id='contentwrapper'>";

		if($this->getMessage() !== null) {

			$html .= "<div class=Msgstatus>$this->message</div>";
		};

		$html .= "
			<div class='image'>
				<img src='./images/$postURL' alt='blogpost'/>
			</div>
			<div id='formwrapper'>
				<form action='?updateimage' method='post' enctype='multipart/form-data'>
					<p>$postCommentText</p>
					Uppdatera bild: 
					<p><input type='file' name='file' id='file' /></p>  
					<input type='hidden' value='$postId' name='update_image'>
					<input type='submit' name='submit' id='updateImageButton' value='Uppdatera bild'>
				</form>
			</div>";
					
		return $html;
	} 

	/**
	 * Metod som returnerar användarens val 
	 */
	public function getAction() {

		switch (key($_GET)) {

			case self::$actionLoginPage:
				$action = self::$actionLoginPage;
				return $action;
				break;

			case self::$actionLogin:
				$action = self::$actionLogin;
				return $action;
				break;

			case self::$actionLogout:
				$action = self::$actionLogout;
				return $action;
				break;

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

	/**
	 * Sätter det aktuella meddelandet 
	 */
	public function setMessage($msg) {

		$this->message = '<p>' . $msg . '</p>';
	}

	/**
	 * Hämtar meddelande
	 */
	 public function getMessage() {

        return $this->message;
    }

    public function getPostedUserName() {

	 	if (empty($_POST[self::$userName])) {

	 		return "";

	 	} else {

       		return $_POST[self::$userName];
    	}
    }

    public function getPostedPassword() {

    	if (empty($_POST[self::$password])) {

    		return "";

    	} else {
        
        	return $_POST[self::$password];
        }
   }

    /**
	 * Hämtar bildfilen
	 */
    public function getImage() {

    	if (isset( $_FILES[self::$imgFile]) && !empty($_FILES[self::$imgFile][self::$imgName])) {

    		return $_FILES[self::$imgFile][self::$imgName];
  		}

  		return NULL; 
    }

    /**
	 * Hämtar temporära bildfilen
	 */
    public function getTempImage() {

    	if (isset( $_FILES[self::$imgFile]) && !empty($_FILES[self::$imgFile][self::$tempName])) {

    		return $_FILES[self::$imgFile][self::$tempName];
  		}

  		return NULL; 
    }

    /**
	 * Hämtar bildens format
	 */
    public function getImageType() {

    	if (isset( $_FILES[self::$imgFile][self::$imgType]) && !empty( $_FILES[self::$imgFile][self::$imgType])) {

    		return $_FILES[self::$imgFile][self::$imgType];
  		}

  		return NULL;
    }

    /**
	 * Hämtar kommentar
	 */
    public function getComment() {

    	if(isset($_POST[self::$strComment])) {

	        return  $_POST[self::$strComment];
	    }

	    return NULL;
    }

    /**
	 * Hämtar namn på användare som postat inlägg
	 */
    public function getPostedBy() {

    	if(isset($_POST[self::$strPostedBy])) {

	        return  $_POST[self::$strPostedBy];
	    }

	    return NULL;
    }

    /**
	 * Hämtar uppdaterade kommentaren
	 */
    public function getUpdatedComment() {

    	if(isset($_POST[self::$updatedComment])) {

	        return  $_POST[self::$updatedComment];
	    }

	    return NULL;
    }

    /**
	 * Hämtar den valda bildens ID
	 */
    public function getImageURL() {

		if (isset($_POST[self::$deleteFile])) {

			return $_POST[self::$deleteFile];
		}

		return NULL;
	}

	/**
	 * Hämtar det valda inläggets ID 
	 */
	 public function getPostId() {

		if (isset($_POST[self::$updateFile])) {

			return $_POST[self::$updateFile];
		}

		return NULL;
	}

	/**
	 * Hämtar den valda kommentarens ID
	 */
	public function getCommentId() {

		if (isset($_POST[self::$commentId])) {

			return $_POST[self::$commentId];
		}

		return NULL;
	}

	/**
	 * Hämtar den valda bildens ID
	 */
	public function getImageId() {

		if (isset($_POST[self::$imageId])) {

			return $_POST[self::$imageId];
		}

		return NULL;
	}
}