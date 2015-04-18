<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        Header("Location: " . URL . "userpanel");
    }
    
}