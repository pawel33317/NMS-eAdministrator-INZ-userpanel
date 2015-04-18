<?php

final class Bootstrap {

    private $_url = null;
    private $_controller = null;
    private $_method = null;
    private $_controllerPath = 'controllers/'; // Always include trailing slash
    private $_modelPath = 'models/'; // Always include trailing slash
    private $_errorFile = 'error.php';

    /**
     * Starts the Bootstrap
     * 
     * @return boolean
     */
    public function init() {
        // Sets the protected $_url
        $this->_getUrl();
        $this->_loadController();
        $this->_callControllerMethod();
    }

    /**
     * Fetches the $_GET from 'url'
     */
    private function _getUrl() {
        $url = isset($_GET['url']) ? $_GET['url'] : 'index';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);
    }

    /**
     * Load an existing controller if there IS a GET parameter passed
     * 
     * @return boolean|string
     */
    private function _loadController() {
        $file = $this->_controllerPath . $this->_url[0] . '.php';

        if (file_exists($file)) {
            require $file;
            $this->_controller = new $this->_url[0];

            //(index, /models)
            $this->_controller->loadModel($this->_url[0], $this->_modelPath);
            unset($this->_url[0]);
        } else {
            $this->_error();
            return false;
        }
    }

    /**
     * If a method is passed in the GET url paremter
     * 
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function _callControllerMethod() {
        $length = count($this->_url);

        // Make sure the method we are calling exists
        //od zera bo byl unset nazwy clasy
        //if method does not exist set default method
        if ($length > 0) {
            if (method_exists($this->_controller, $this->_url[1])) {
                $this->_method = $this->_url[1];
                unset($this->_url[1]);
            } else {
                $this->_error();
            }
        } else {
            $this->_method = 'index';
        }

        //list of function parameters
        $this->parms = $this->_url ? array_values($this->_url) : [];
        //execute the object function and send parameters
        call_user_func_array([$this->_controller, $this->_method], $this->parms);
    }
    /**
     * Display an error page if nothing exists
     * 
     * @return boolean
     */
    private function _error() {
        require $this->_controllerPath . $this->_errorFile;
        $this->_controller = new Error();
        $this->_controller->index();
        exit;
    }

}