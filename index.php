<?php

require_once("src/controller/PostController.php");
require_once("src/view/HTMLView.php");

session_start();

$postController = new \controller\PostController();
$htmlBodyPost = $postController->doControl();

$viewPost = new \view\HTMLview();
$viewPost->echoHTML($htmlBodyPost);

