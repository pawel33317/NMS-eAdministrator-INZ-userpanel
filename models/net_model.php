<?php

class Net_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function existIP($ip) {
        $sth = $this->db->select('SELECT id FROM devices WHERE ip = :ip', array('ip' => $ip));

        if (isset($sth[0]['id']))
            return true;
        else
            return false;
    }

}
