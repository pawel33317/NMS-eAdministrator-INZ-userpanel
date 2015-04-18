<?php

class User_Auth extends Controller{

    function __construct() {
        parent::__construct();
        parent::loadModel('user_auth');
    }
    /**
     * Check if the user is logged in 
     * @return boolean
     */
    public function isUserLogged() {
        if (isset($_COOKIE['user_id'])) {
            $pass = $this->model->getPassword($_COOKIE['user_id']);
            if ($pass) {
                if ($pass == $_COOKIE['user_pass']) {
                    //przedłóżenie
                    $this->logInUser($_COOKIE['user_id'], $_COOKIE['user_pass']);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    
    /**
     * Send cookie with user data
     * @param type $userID user id 
     * @param type $userPassMd5 user md5 password
     */
    public function logInUser($userID, $userPassMd5){
        setcookie("user_id", $userID, time() + 360000,'/');
        setcookie("user_pass", $userPassMd5, time() + 360000,'/');
    }

    /**
     * Check if the device is already registered
     * @param string $mac MAC address
     * @return boolean
     */
    public function isDeviceRegistered($mac) {
        $user = $this->model->getUserByDevice($mac);
        if ($user) {
            $this->logInUser($user['user_id'], $user['user_pass']);
            return $user;
        } else {
            return false;
        }
    }
/**
 * Get user data login, imie, nazwisko
 * @param int $id user ID 
 * @return array  
 */
    public function getUserData($id) {
       return $this->model->getUserData($id);   
    }
    
}
