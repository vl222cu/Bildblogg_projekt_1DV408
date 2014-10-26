<?php

namespace model;

require_once("./src/model/LoginRepository.php");

class LoginModel {

	private $userName = 'username';
    private $password = 'password';
    private $loggedIn = 'LoggedIn';
    private $userAgent = 'userAgent';
    private $HTTPUserAgent = 'HTTP_USER_AGENT';
    private $loginRepository;

    public function __construct() {

        $this->loginRepository = new LoginRepository();

    }
    
    /** 
     * Skickar användaruppgifter till databasen
     */
    public function authenticateUser($userName, $password) {

        $ret = $this->loginRepository->getUserCredentialsFromDB($userName, $password);

        if ($ret) {
 
            return true;

        } else {

            return false;
        }
    }

    /** 
     * Sätter sessionsnamn om användaren lyckas logga in
     */
   	public function setSessionVariables() {

		$_SESSION[$this->loggedIn] = true;
        $_SESSION[$this->userName] = $this->userName;
        $_SESSION[$this->userAgent] = $_SERVER[$this->HTTPUserAgent];

    }

    /** 
     * Hämtar sessioninformation
     */
    public function getSessionUsername() {

        if(isset($_SESSION[$this->userName])) {

            return $_SESSION[$this->userName];
        }
    }

    /** 
     * Kollar om användaren är inloggad
     */
    public function userIsLoggedIn() {

		if (isset($_SESSION[$this->loggedIn]) && $_SESSION[$this->loggedIn]) {

			return true;		
		}

		return false;
	}

    /** 
     * Hämtar HTTP-Agentinformation
     */
	public function getSessionControl() {

		if ($_SESSION[$this->userAgent] === $_SERVER[$this->HTTPUserAgent]) {

			return true;
		}

		return false;
	}

	public function logOut() {

		unset($_SESSION[$this->loggedIn]);
	  	unset($_SESSION[$this->userName]);
	  	session_destroy();
	}
}