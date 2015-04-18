<?php

class Form {

    /** @var array $_currentItem The immediately posted item */
    private $_currentItem = null;

    /** @var array $_postData Stores the Posted Data */
    private $_postData = array();

    /** @var array $_error Holds the current forms errors */
    private $_error = array();

    public function __construct() {
        
    }

    /**
     * Put data into array
     * 
     * @param string $field $_POST['$field'] name
     * @return \Form 
     */
    public function post($field) {
        $this->_postData[$field] = htmlspecialchars($_POST[$field]);
        $this->_currentItem = $field;
        return $this;
    }

    /**
     * Get $POST data
     * @param type $fieldName
     * @return boolean
     */
    public function fetch($fieldName = false) {
        if ($fieldName) {
            if (isset($this->_postData[$fieldName]))
                return $this->_postData[$fieldName];
            else
                return false;
        } else {
            return $this->_postData;
        }
    }

    /**
     * Validate the data
     * @param string $info callback when validation error
     * @param string $typeOfValidator validator name
     * @param mixed $arg additional info for validator
     * @return \Form return this (object Form)
     */
    public function val($info = 'Błąd w danych', $typeOfValidator, $arg = null) {
        if ($arg == null)
            $error = $this->{$typeOfValidator}($info, $this->_postData[$this->_currentItem]);
        else
            $error = $this->{$typeOfValidator}($info, $this->_postData[$this->_currentItem], $arg);

        if ($error)
            $this->_error[$this->_currentItem] = $error;

        return $this;
    }

    /**
     * Return true when no errors or exception with errors
     * 
     * @return boolean
     * @throws ArrayException array of errors
     */
    public function submit() {
        if (empty($this->_error)) {
            return true;
        } else {
            $errorList = array();
            foreach ($this->_error as $key => $value) {
                array_push($errorList, $value);
            }
            throw new ArrayException($errorList, 'to get errors run method getErrorArray()');
        }
    }

    public function minlength($info, $data, $arg) {
        if (strlen($data) < $arg) {
            return $info;
        }
    }

    public function maxlength($info, $data, $arg) {
        if (strlen($data) > $arg) {
            return $info;
        }
    }

    public function digit($info, $data) {
        if (ctype_digit($data) == false) {
            return $info;
        }
    }

    public function existRoomNr($info, $data) {
        if (ctype_digit($data) && $data > 721) {
            return $info;
        }
    }

    public function theSame($info, $data, $data2 = null) {
        if ($data != $data2) {
            return $info;
        }
    }

    public function __call($name, $arguments) {
        throw new Exception("$name does not exist inside of: " . __CLASS__);
    }

}
