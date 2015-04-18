<?php

class ArrayException extends Exception {

    private $_errorsArray;

    /**
     * __construct
     * @param array $errorArray Array Error list
     * @param string $message One array 
     * @param mixed $code Error code
     * @param Exception $previous pointer on previous exception
     */
    

    public function __construct($errorArray = array('params'), $message = 0, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->_errorsArray = $errorArray;
    }

    /**
     * Get error array 
     * @return array
     */
    public function getErrorArray() {
        return $this->_errorsArray;
    }

}
