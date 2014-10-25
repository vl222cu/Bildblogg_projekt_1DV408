<?php

namespace controller;

require_once("./src/view/PostView.php");
require_once("./src/model/PostModel.php");
require_once("./src/model/PostRepository.php");
require_once("./src/view/ErrorPageView.php");

class PostController {

	private $postView;
	private $showPostView;
	private $postModel;
	private $postRepository;
	private $errorPageView;

	public function __construct() {

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
	 * Kontrollerar funktion för uppladdning av bild och kommentar till server och databas
	 */
	public function upLoadPost() {

		/**
		 * Validerar först bildformat och bildstorlek innan bilden sparas
		 */
		if ($this->postModel->isValidImage($this->postView->getImageType()) && $this->postModel->checkImageSize($this->postView->getTempImage())) {

			if ($this->postRepository->saveImage($this->postView->getPostedBy(), $this->postView->getImage(), $this->postView->getComment())) {

			//	$this->postModel->setClientIdentifier($clientIdentifier);
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

		$images = $this->postRepository->getAllImagesFromDB();
			
		return $this->postView->showAllImagesHTML($images);		
	}

	/**
	 * Tar bort vald bild med tillhöranade kommentar
	 */
	public function deletePost() {

		if ($this->postModel->getTargetImgId() || $this->postModel->getTargetCommentId()) {

			if ($this->postRepository->deletePostFromDB($this->postView->getImageURL())) {

				$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_SUCCESSED);

				return $this->showAllPosts();

			} else {

				$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_FAILED);

				return $this->showAllPosts();
			}

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_NOT_ALLOWED);

			return $this->showAllPosts();
		}
	}

	/**
	 * Returnerar HTML-sida för uppdatering av kommentar
	 */
	public function updatePostedCommentPage() {

		if ($this->postModel->getTargetImgId() || $this->postModel->getTargetCommentId()) {	

			$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getPostId());

			return $this->postView->updateCommentPageHTML($selectedPost);

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_UPDATE_COMMENT_NOT_ALLOWED);

			return $this->showAllPosts();

		}
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

		if ($this->postModel->getTargetImgId() || $this->postModel->getTargetCommentId()) {	
		
			$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getPostId());

			return $this->postView->updateImagePageHTML($selectedPost);

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_UPDATE_IMAGE_NOT_ALLOWED);

			return $this->showAllPosts();
		}

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