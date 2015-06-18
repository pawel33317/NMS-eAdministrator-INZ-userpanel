<?php

class DeviceRegister extends Controller {

    function __construct() {
        parent::__construct();
        $this->userAuth = new User_Auth();
        $this->view->info = array();
        
        $this->NetSettings = new Net();
        try {
            $this->mac = $this->NetSettings->getMAC();
            //$this->mac = Net::getMAC();
        } catch (Exception $e) {
            Header("Location: " . URL . "userpanel");
        }
    }

    function init() {
        if (!$this->userAuth->isUserLogged() || $this->userAuth->isDeviceRegistered($this->mac)) {
            Header("Location: " . URL . "userpanel");
        }
        $this->view->title = 'Rejestracja urządzenia';
        $this->view->render('header');
        array_push($this->view->info, array('type' => 'success', 'text' => 'Rejestracja urządzenia w sieci'));

        $user = $this->userAuth->getUserData($_COOKIE['user_id']);
        array_push($this->view->info, array('type' => 'success',
            'text' => 'Zalogowany jako: <strong> ' . $user["imie"] . ' ' . $user["nazwisko"] . ' - ' . $user["login"] . ' </strong>'));

        array_push($this->view->info, array('type' => 'info', 'text' => 'Przejdź do panelu zarządzania urządzeniami. '
            . '<a href=' . URL . 'userpanel>LINK</a>'));
    }

    function deviceDelete($devNr) {
        if (!$this->userAuth->isUserLogged()) {
            Header("Location: " . URL . "userpanel");
        }

        $devOwnerId = $this->model->getDeviceOwnerId($devNr);

        if ($_COOKIE['user_id'] != $devOwnerId) {
            echo 'Nie jesteś właścicielem urządzenia więc nie masz prawa go usunąć.';
            die();
        } else {
            $this->model->removeDevice($devNr);
            Header("Location: " . URL . "userpanel");
        }
    }

    function register() {
        if (isset($_POST['devname'])) {
            try {

                if ($this->userAuth->isDeviceRegistered($this->mac)) {
                    throw new ArrayException(array('Urządzenie jest już zarejestrowane.'));
                }

                $form = new Form();
                //wrzuca do $form dane i wymusza na nich walidację
                $form->post('devtype')
                        ->val('Typ urządzenia jest zbyt krótki.', 'minlength', 2)
                        ->val('Typ urządzenia  jest zbyt długi.', 'maxlength', 100)
                        ->post('devname')
                        ->val('Nazwa urządzenia jest zbyt krótka.', 'minlength', 3)
                        ->val('Nazwa urządzenia jest zbyt długa.', 'maxlength', 30);
                $form->submit();
                //pobiera tablicę asocjacyjną ze zwalidowanymi danymi
                $data = $form->fetch();

                //dopisuje do tablicy
                $data['stan'] = 0;
                $data['dateadd'] = @strtotime("now");
                $data['mac'] = $this->mac;
                $data['user_id'] = $_COOKIE['user_id'];
                $net = new Net();
                $data['ip'] = $net->getNewIP();
                //dodaje urządzenie do bazy
                $regNewDev = $this->model->registerDevice($data);
                if ($regNewDev !== true) {
                    throw new ArrayException(array('Pojawił się problem z bazą danych. Skontaktuj się z administratorem.'));
                }

                $this->NetSettings->newDevice();
                
                
                Header("Location: " . URL . "userpanel/startInfo");
            } catch (ArrayException $e) {
                $this->init();

                //pobiera listę errorów walidacji danych
                $errors = $e->getErrorArray();
                //wyświetla ładnie errory
                foreach ($errors as $error) {
                    array_push($this->view->info, array('type' => 'danger', 'text' => $error));
                }
                $this->view->render('userpanel/info');
                $this->view->render('userpanel/panel_urzadzenia');
                //dodac panel dodania urzadzenia

                $this->view->render('footer');
            }
        } else {
            Header("Location: " . URL . "userpanel");
        }
    }

    function index() {
        $this->init();
        $this->view->render('userpanel/info');
        $this->view->render('userpanel/panel_urzadzenia');

        $this->view->info = array();
        array_push($this->view->info, array('type' => 'warning', 'text' => 'Każde urządzenie musi być zarejestrowane osobno, nie wolno '
            . 'rejestrować 2 razy tego samego urządzenia, w razie problemów zgłosić się do administratora pokój 401.'));
        $this->view->render('userpanel/info');
        $this->view->render('footer');
    }

}
