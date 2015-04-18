<?php

class DeviceRegister_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function registerDevice($data) {
        return $this->db->insert('devices',$data);
    }
    
    public function getDeviceOwnerId($devId) {
        $sth = $this->db->select('SELECT user_id FROM devices WHERE id = :id', array('id' => $devId));
        if (isset($sth[0]['user_id']))
            return $sth[0]['user_id'];
        else
            return false;
    }
    
    public function removeDevice($id)
    {
        $this->db->delete('devices', "`id` = $id");
    }
}
