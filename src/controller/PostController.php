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

	public function doControl() {

		$userAction = $this->postView->getAction();

		try {

			switch ($userAction) {

				case \view\PostView::$actionUploadPage:
					$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPLOAD_SUCCESSED);
					return $this->postView->uploadPageHTML();
					break;

				case \view\PostView::$actionUpload:
					return $this->upLoadImage();
					break;

				case \view\PostView::$actionReturn:
					return $this->postView->mainPageHTML();
					break;

				default: 
					return $this->postView->mainPageHTML();
			}

		} catch (\Exception $e) {

			throw $e;
		/*	\view\NavigationView::RedirectToErrorPage();
			die(); */
		} 
	}

	public function upLoadImage() {

		if($this->postModel->isValidImage($this->postView->getImageType())) {

			$this->postRepository->saveImage($this->postView->getImage(), $this->postView->getComment());
			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPLOAD_SUCCESSED);

			return $this->postView->uploadPageHTML();

		} else {

			$this->postView->setMessage(\view\PostView::MESSAGE_ERROR_UPLOAD_FAILED);

			return $this->postView->uploadPageHTML();
		}

	}
}