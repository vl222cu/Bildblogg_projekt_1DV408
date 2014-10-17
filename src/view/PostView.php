<?php

namespace view;

class PostView {

	private $postModel;
	public static $actionUpload = 'upload';


	public function __construct(\model\PostModel $postModel) {
		
		$this->postModel = $postModel;
	}

	public function mainPageHTML() {

		$html = "
		 	<div id='maincontainer'>
		 		<div id='content'>
		 			<h1>Vivis bildblogg</h1>
			 		<div id='formwrapper'>
				 		<form action='?upload' method='post' enctype='multipart/form-data'>
							<input type='hidden' name='xsubmit' value='y' />
							<input type='file' name='file' id='file' />  
							<input type='text' name='location' id='location' placeholder='Lägg till kommentar' /> 
							<input type='submit' name='submit' id='uploadButton' value='Ladda upp' />
						</form>
					</div>
				</div>
		 	</div>";

		return $html;

	}

	public function getAction() {

		switch (key($_GET)) {

			case self::$actionUpload:
				$action = self::$actionUpload;
				return $action;
				break;

			default:
				$action = "";

		}
	}
}