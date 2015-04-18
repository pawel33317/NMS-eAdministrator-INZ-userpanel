<?php

class User_Auth_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getPassword($userID, $searchTable = 'id') {
        $sth = $this->db->select('SELECT haslo h FROM users WHERE ' . $searchTable . ' = :id', array('id' => $userID));
        if (isset($sth[0]['h']))
            return $sth[0]['h'];
        else
            return false;
    }


    public function getUserByDevice($mac) {
        $sth = $this->db->select('select u.id as user_id, u.haslo as user_pass from users as u '
                . 'join devices as d on u.id = d.user_id where d.mac = :mac', array('mac' => $mac));
        if (isset($sth[0]))
            return $sth[0];
        else
            return false;
    }

    public function getUserData($userID) {
        $sth = $this->db->select('SELECT imie, nazwisko, login FROM users WHERE id = :id', array('id' => $userID));
        if (isset($sth[0]))
            return $sth[0];
        else
            return false;
    }
}
