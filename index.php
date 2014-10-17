<?php

require_once("src/controller/PostController.php");
require_once("src/view/HTMLView.php");

session_start();

$controller = new \controller\PostController();
$htmlBody = $controller->doControl();

$view = new \view\HTMLview();
$view->echoHTML($htmlBody);