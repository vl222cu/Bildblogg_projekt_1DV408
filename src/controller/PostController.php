<?php

namespace controller;

require_once("./src/view/PostView.php");
require_once("./src/model/PostModel.php");

class PostController {

	private $postView;
	private $postModel;

	public function __construct() {

		$this->postModel = new \model\PostModel();
		$this->postView = new \view\PostView($this->postModel);

	}

	public function doControl() {

		$userAction = $this->postView->getAction();

		try {

			switch ($userAction) {

				case \view\PostView::$actionUpload:
					return $this->upLoadImage();
					break;

				default: 
					return $this->postView->MainPageHTML();
			}

		} catch (\Exception $e) {

			throw $e;
		/*	\view\NavigationView::RedirectToErrorPage();
			die(); */
		} 
	}

	public function upLoadImage() {

		if($this->postModel->isValidImage()) {

			

		}

	}
}