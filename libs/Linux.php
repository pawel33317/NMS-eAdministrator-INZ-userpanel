<?php

class Linux
{
    public static $log_DIR = '/var/www/log_to_php_panel/';
    public static $service_state_DIR = '/var/www/service_state/';
    public static $shScripts = '/var/www/shscripts/';
    public static $fileToReload = '/var/www/file_to_check_to_reload/';

    public static function newDevice(){
        $file = Linux::$fileToReload_newUSR;
        $linuxOperation = `echo 1 > $file'newusr.s'`;
    }

}