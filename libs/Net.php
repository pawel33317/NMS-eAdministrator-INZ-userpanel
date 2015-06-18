<?php

class Net extends Controller{
    function __construct() {
        parent::__construct();
        parent::loadModel('net');
    }
    /**
     * Get host MAC address
     * 
     * @return string host MAC address
     */
    public function getMAC() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ShowRealMac = true;

	if (!$ShowRealMac){
		return '11:22:33:44:55:64';
	}else{
		$arp = `sudo arp -a -n $ip`;
		if (!isset($arp)) {
		   throw new Exception('Arp commend error');
		}
		$lines = explode("\n", $arp);
		if (empty($lines[0])) {
		   throw new Exception('Arp parsing error 1');
		}
		$tmp = explode('at ', $lines[0]);
		if (empty($tmp[1])) {
		    throw new Exception('Arp parsing error 2');
		}
		$tmp = explode(' [', @$tmp[1]);
		if (empty($tmp[0])) {
		    throw new Exception('Arp parsing error 3');
		}
			//echo $tmp[0];die();
		   return $tmp[0];       //return 'aa:aa:aa:aa:aa:ab';
	}
    }
    /**
     * Gen free IP address
     * @return mixed IPaddress or false
     */
    public function getNewIP() {
        $oktet1 = 10;
        $oktet2 = 0;
        $oktet3 = 0;
        $oktet4 = 1;

        $lastOctet = 1;
        $foundedIP = false;
        $secondLast = 0;
        while ($secondLast < 4 && $foundedIP == false) {
            $lastOctet = 2;
            $oktet3 = $secondLast;
            while ($lastOctet < 255 && $foundedIP == false) {
                $oktet4 = $lastOctet;
                $result = $this->model->existIP($oktet1 . '.' . $oktet2 . '.' . $oktet3 . '.' . $oktet4);
                if (!$result) {
                    $foundedIP = true;
                }
                $lastOctet++;
            }
            $secondLast++;
        }

        if ($foundedIP == true) {
            return $oktet1 . '.' . $oktet2 . '.' . $oktet3 . '.' . $oktet4; //return '10.0.0.111';
        } else {
            return false;
        }
    }
    
    public function newDevice(){
        $file = Linux::$fileToReload_newUSR;
        $linuxOperation = `echo 1 > $file'newusr.s'`;
    }

}
