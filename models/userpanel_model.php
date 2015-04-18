<?php

class Userpanel_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function macState($mac) {

        $sth = $this->db->select('SELECT stan FROM devices WHERE mac = :mac', array('mac' => $mac));

        if (isset($sth[0]['stan']))
            return $sth[0]['stan'];
        else
            return false;
    }

    public function getPassword($userID, $searchTable = 'id') {
        $sth = $this->db->select('SELECT haslo h FROM users WHERE ' . $searchTable . ' = :id', array('id' => $userID));
        if (isset($sth[0]['h']))
            return $sth[0]['h'];
        else
            return false;
    }

    public function updateAccountWalidity($userID, $newTime) {
        $sth = $this->db->update('users', array('datawaznoscikonta' => $newTime), '`id` = ' . $_COOKIE['user_id']);
        echo json_encode($sth);
    }

    public function getAccountWalidity($userID) {
        $sth = $this->db->select('SELECT datawaznoscikonta FROM users WHERE id = :id', array('id' => $userID));
        if (isset($sth[0]['datawaznoscikonta']))
            return $sth[0]['datawaznoscikonta'];
        else
            return false;
    }

    public function getUserDevices($userID) {
        $sth = $this->db->select('SELECT id, ip, devtype, devname FROM devices WHERE user_id = :id', array('id' => $userID));
        if (isset($sth))
            return $sth;
        else
            return false;
    }

    public function getUserID($login) {
        $sth = $this->db->select('SELECT id FROM users WHERE login = :login', array('login' => $login));
        if (isset($sth[0]['id']))
            return $sth[0]['id'];
        else
            return false;
    }

    public function registerNewUser($data) {
        return $this->db->insert('users',$data);
    }
}
