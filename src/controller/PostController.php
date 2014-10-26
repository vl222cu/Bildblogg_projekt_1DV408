<?php

namespace controller;

require_once("./src/model/LoginModel.php");
require_once("./src/model/LoginRepository.php");
require_once("./src/view/PostView.php");
require_once("./src/model/PostModel.php");
require_once("./src/model/PostRepository.php");
require_once("./src/view/ErrorPageView.php");

class PostController {

	private $loginModel;
	private $loginRepository;
	private $postView;
	private $showPostView;
	private $postModel;
	private $postRepository;
	private $errorPageView;

	public function __construct() {

		$this->loginModel = new \model\LoginModel();
		$this->loginRepository = new \model\LoginRepository();
		$this->postModel = new \model\PostModel();
		$this->postRepository = new \model\PostRepository();
		$this->postView = new \view\PostView($this->postModel);
		$this->errorPageView = new \view\ErrorPageView();

	}

	/** 
	 * Kollar vilken funktion som ska anropas beroende på användarens val i vyn 
	 */
	public function doControl() {

		$userAction = $this->postView->getAction();

		try {

			switch ($userAction) {

				case \view\PostView::$actionLoginPage:
					return $this->postView->showLoginPageHTML();
					break;

				case \view\PostView::$actionLogin:
					return $this->loginUser();
					break;

				case \view\PostView::$actionLogout:
					return $this->logoutUser();
					break;

				case \view\PostView::$actionUploadPage:
					return $this->postView->uploadPageHTML();
					break;

				case \view\PostView::$actionUpload:
					return $this->upLoadPost();
					break;

				case \view\PostView::$actionReturn:
					return $this->showAllPosts();
					break;

				case \view\PostView::$actionDelete:
					return $this->deletePost();
					break;

				case \view\PostView::$actionUpdateCommentPage:
					return $this->updatePostedCommentPage();
					break;

				case \view\PostView::$actionUpdateComment:
					return $this->updatePostedComment();
					break;

				case \view\PostView::$actionUpdateImagePage:
					return $this->updatePostedImagePage();
					break;

				case \view\PostView::$actionUpdateImage:
					return $this->updatePostedImage();
					break;

				default: 
					return $this->showAllPosts();
			}

		} catch (\Exception $e) {

			$this->errorPageView->errorHTML();
			die(); 
		} 
	}

	/**
	 * Loggar in användaren
	 */
	public function loginUser() {

		/** 
		 * Förhindrar sessionstöld 
	 	*/
		if ($this->loginModel->userIsLoggedIn()) {

			if ($this->loginModel->getSessionControl() == false) {

				return $this->postView->showLoginPageHTML();

			} else {

				return $this->showAllPosts();
			}

		}

		if ($this->loginModel->authenticateUser($this->postView->getPostedUserName(), $this->postView->getPostedPassword())) {

				$this->loginModel->setSessionVariables();
				$this->postView->setMessage(\view\PostView::MESSAGE_SUCCESS_LOGIN);							

				return $this->showAllPosts();

		} else {
						
			if ($this->postView->getPostedUserName() == "") {

				$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_USERNAME);

			} elseif ($this->postView->getPostedPassword() == "") {

				$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_PASSWORD);

			} else {

				$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_USERNAME_PASSWORD);
			} 					
			
			return $this->postView->showLoginPageHTML();
		} 
	}

	/**
	 * Loggar ut användaren
	 */
	public function logoutUser() {

		$this->loginModel->logout();
		$this->postView->setMessage(\view\PostView::MESSAGE_SUCCESS_LOGOUT);

		return $this->showAllPosts();
	}	

	/**
	 * Kontrollerar funktion för uppladdning av bild och kommentar till server och databas
	 */
	public function upLoadPost() {

		/**
		 * Validerar först bildformat och bildstorlek innan bilden sparas
		 */
		if ($this->postModel->isValidImage($this->postView->getImageType()) && $this->postModel->checkImageSize($this->postView->getTempImage())) {

			if ($this->postRepository->saveImage($this->postView->getPostedBy(), $this->postView->getImage(), $this->postView->getComment())) {

				$this->postView->setMessage(\view\PostView::MESSAGE_UPLOAD_SUCCESSED);

				return $this->showAllPosts(); 

			} else {

				$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPLOAD_TO_SERVER);

				return $this->postView->uploadPageHTML();
			}

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPLOAD_FAILED);

			return $this->postView->uploadPageHTML();
		}

	}

	/**
	 * Visar alla bilder och kommentarer som är sparade
	 */
	public function showAllPosts() {

		if ($this->loginModel->userIsLoggedIn()) {
			
			$images = $this->postRepository->getAllImagesFromDB();
				
			return $this->postView->showAllImagesWithCRUDHTML($images);	

		} else {

			$images = $this->postRepository->getAllImagesFromDB();
				
			return $this->postView->showAllImagesHTML($images);	
		}	
	}

	/**
	 * Tar bort vald bild med tillhöranade kommentar
	 */
	public function deletePost() {

		if ($this->postRepository->deletePostFromDB($this->postView->getImageURL())) {

			$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_SUCCESSED);

			return $this->showAllPosts();

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_FAILED);

			return $this->showAllPosts();
		}
	}

	/**
	 * Returnerar HTML-sida för uppdatering av kommentar
	 */
	public function updatePostedCommentPage() {

		$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getPostId());

		return $this->postView->updateCommentPageHTML($selectedPost);
	}

	/**
	 * Kontrollerar funktion för uppdatering av kommentar
	 */
	public function updatePostedComment() {

		if ($this->postRepository->editSelectedComment($this->postView->getCommentId(), $this->postView->getUpdatedComment())) {

			$this->postView->setMessage(\view\PostView::MESSAGE_UPDATE_COMMENT_SUCCESSED);

			return $this->showAllPosts(); 

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPDATE_COMMENT_FAILED);

			$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getCommentId());

			return $this->postView->updateCommentPageHTML($selectedPost);
		}
	}

	/**
	 * Returnerar HTML-sida för uppdatering av bild
	 */
	public function updatePostedImagePage() {
			
		$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getPostId());

		return $this->postView->updateImagePageHTML($selectedPost);
	}

	/**
	 * Kontrollerar funktion för uppdatering av bild
	 */
	public function updatePostedImage() {

		/**
		 * Validerar först bildformat och bildstorlek innan bilden sparas
		 */
		if ($this->postModel->isValidImage($this->postView->getImageType()) && $this->postModel->checkImageSize($this->postView->getTempImage())) {

			if ($this->postRepository->editSelectedImage($this->postView->getImageId(), $this->postView->getImage())) {

				$this->postView->setMessage(\view\PostView::MESSAGE_UPDATE_IMAGE_SUCCESSED);

				return $this->showAllPosts(); 

			} else {

				$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPDATE_IMAGE_TO_SERVER);

				$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getImageId());

				return $this->postView->updateImagePageHTML($selectedPost);
			}

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPDATE_IMAGE_FAILED);

			$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getImageId());

			return $this->postView->updateImagePageHTML($selectedPost);
		}		
	}
}