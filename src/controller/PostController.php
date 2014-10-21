<?php

namespace controller;

require_once("./src/view/PostView.php");
require_once("./src/model/PostModel.php");
require_once('./src/model/PostRepository.php');

class PostController {

	private $postView;
	private $postModel;
	private $postRepository;

	public function __construct() {

		$this->postModel = new \model\PostModel();
		$this->postRepository = new \model\PostRepository();
		$this->postView = new \view\PostView($this->postModel);

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

				default: 
					return $this->showAllPosts();
			}

		} catch (\Exception $e) {

			throw $e;
		/*	\view\NavigationView::RedirectToErrorPage();
			die(); */
		} 
	}

	/**
	 * Uppladdning av bild och kommentar till server och databas
	 */
	public function upLoadPost() {

		/**
		 * Validerar först bildformat och bildstorlek innan bilden sparas
		 */
		if ($this->postModel->isValidImage($this->postView->getImageType()) && $this->postModel->checkImageSize($this->postView->getTempImage())) {

			if ($this->postRepository->saveImage($this->postView->getImage(), $this->postView->getComment())) {

				$this->postView->setMessage(\view\PostView::MESSAGE_UPLOAD_SUCCESSED);

				return $this->postView->uploadPageHTML(); 

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

		if ($this->postRepository->deletePostFromDB($this->postView->getImageURL())) {

			$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_SUCCESSED);

			return $this->showAllPosts();

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_DELETE_FAILED);

			return $this->showAllPosts();
		}

	}

	public function updatePostedCommentPage() {

		$selectedPost = $this->postRepository->getSelectedPostToEdit($this->postView->getPostId());

		return $this->postView->updateCommentHTML($selectedPost);

	}

	public function updatePostedComment() {

		if ($this->postRepository->editSelectedComment($this->postView->getCommentId(), $this->postView->getUpdatedComment())) {

			$this->postView->setMessage(\view\PostView::MESSAGE_UPDATE_COMMENT_SUCCESSED);

			return $this->showAllPosts(); 

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPDATE_COMMENT_FAILED);

			return $this->showAllPosts();

		}
	}

/*	public function updatePost() {

		if ($this->postModel->isValidImage($this->postView->getImageType()) && $this->postModel->checkImageSize($this->postView->getTempImage())) {

			if ($this->postRepository->editSelectedPost($this->postView->getPostId(), $this->postView->getImage(), $this->postView->getComment())) {

				$this->postView->setMessage(\view\PostView::MESSAGE_UPDATE_SUCCESSED);

				return $this->showAllPosts(); 

			} else {

				$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPDATE_TO_SERVER);

				return $this->updatePostPage();
			}

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPDATE_FAILED);

			return $this->updatePostPage();
		}
	} */
}