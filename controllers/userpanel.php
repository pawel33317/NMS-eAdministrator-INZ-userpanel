<?php

class Userpanel extends Controller {

    function __construct() {
        parent::__construct();

        $this->userAuth = new User_Auth();

        //Creata array for info
        $this->view->info = array();

        //Get user mac or pring error page
        $this->NetSettings = new Net();
        try {
            $this->mac = $this->NetSettings->getMAC();
        } catch (Exception $e) {
            $this->view->render('header');
            array_push($this->view->info, array('type' => 'danger', 'text' => 'Nie można pobrać adresu MAC skontaktuj się z administratorem. '));
            array_push($this->view->info, array('type' => 'danger', 'text' => $e->getMessage()));
            $this->view->render('userpanel/info');
            $this->view->render('footer');
            die();
        }
    }

    /**
     * Add info about user and device to array
     * Check if user is banned
     */
    function init() {
        //is user mac banned pring error page
        $mac = $this->model->macState($this->mac);
        if ($mac == 2) {
            Header("Location: " . URL . "userpanel/banned");
        }

        //if user is logged in add info to array or add info about unlogging
        if ($this->userAuth->isUserLogged()) {
            $user = $this->userAuth->getUserData($_COOKIE['user_id']);
            array_push($this->view->info, array('type' => 'success',
                'text' => 'Zalogowany jako: <strong> ' . $user["imie"] . ' ' . $user["nazwisko"] . ' - ' . $user["login"] . ' </strong>'));

            $accountWalidity = $this->model->getAccountWalidity($_COOKIE['user_id']);
            array_push($this->view->info, array('type' => 'info',
                'text' => 'Twoje konto jest ważne do: <strong> ' . @date("h:i, d-m-Y", $accountWalidity) . ' </strong>'));
        } else {
            array_push($this->view->info, array('type' => 'info', 'text' => 'Nie jesteś zalogowany.'));
        }

        //info about device is registered already
        if ($this->userAuth->isDeviceRegistered($this->mac)) {
            array_push($this->view->info, array('type' => 'info', 'text' => 'To urządzenie jest już zarejestrowane.'));
            if (!isset($_COOKIE['user_id'])) {
                Header("Location: " . URL . "userpanel");
            }
        } else
            array_push($this->view->info, array('type' => 'info', 'text' => 'To urzędzenie jeszcze nie jest zarejestrowane.'));
    }

    /**
     * When user send POST data to login 
     * If data OK log in user and header to default page
     */
    function login() {
        if (isset($_POST['user_name'])) {
            $pass = $this->model->getPassword($_POST['user_name'], 'login');
            if (!$pass) {
                Header("Location: " . URL . "userpanel/loginerror");
            } else {
                if ($pass == md5($_POST['user_pass'])) {
                    $id = $this->model->getUserID($_POST['user_name']);
                    $this->userAuth->logInUser($id, md5($_POST['user_pass']));
                    Header("Location: " . URL . "userpanel");
                } else {
                    Header("Location: " . URL . "userpanel/loginerror");
                }
            }
        }
    }

    /**
     * Renew account Walidity
     */
    function renewalAccount() {
        if (isset($_POST['lengthen'])) {
            $newTimeAccountWalidity = strtotime("now") + 60 * 60 * 24 * 91;
            $query = $this->model->updateAccountWalidity($_COOKIE['user_id'], $newTimeAccountWalidity);
        }
        Header("Location: " . URL . "userpanel");
    }

    /**
     * Page when user login data error
     */
    function loginerror() {
        $this->view->render('header');
        $this->init();
        array_push($this->view->info, array('type' => 'info', 'text' => 'Nie mam konta utwórz nowe. ',
            'boldtext' => '<a href=' . URL . 'userpanel/registerPanel>LINK </a>'));
        array_push($this->view->info, array('type' => 'danger',
            'boldtext' => 'Dane logownaia niepoprawne.'));
        $this->view->render('userpanel/info');
        $this->view->render('userpanel/panel_logowania');
        $this->view->render('footer');
    }

    /**
     * page when user already registered his device
     */
    function startInfo() {
        $this->index('startInfo');
    }

    /**
     * page when user try to log in
     */
    function loginPanel() {
        $this->index('loginPanel');
    }

    /**
     * Page when user try to register
     */
    function registerPanel() {
        $this->index('registerPanel');
    }

    /**
     * 
     * @param type $showDefault user page
     */
    function index($show = null) {
        $this->view->render('header');
        $this->init();

        //info po rejestracji urządzenia
        if ($show == 'startInfo')
            array_push($this->view->info, array('type' => 'info', 'text' => 'W ciągu kilku sekund internet powinien zacząć działać. 
                Należy wyłączyć i włączyć kartę sieciową lub wyjąć kabel na 10 sekund i włożyć go ponownie,
                w przypadku połączenia wifi należy rozłączyć się z siecią i połączyć ponownie po upływie kilku sekund.'));

         //jezeli user zalogowany a urzadzenie nie zarejestrowane opcja dodania urządzenia
        if ($this->userAuth->isUserLogged() && !$this->userAuth->isDeviceRegistered($this->mac))
            array_push($this->view->info, array('type' => 'info', 'text' => 'Przejdź do panelu dodania urządzenia. '
                . '<a href=' . URL . 'deviceregister>LINK</a>'));

        $this->view->render('userpanel/info');
        $this->view->info = array();
        
        //jak zalogowany to panele urzadzen i ustawien
        if ($this->userAuth->isUserLogged()) {
            $this->view->userDevices = $this->model->getUserDevices($_COOKIE['user_id']);
            $this->view->render('userpanel/user_devices');
            $this->view->render('userpanel/user_settings');
        }
        
        //pokazuje mozlowość rejestracji lub logowania
        else {
            if ($show == 'loginPanel')
                $this->view->render('userpanel/panel_logowania');
            else
                array_push($this->view->info, array('type' => 'info', 'text' => 'Mam już konto zaloguj (rejestrowałem się na innym urządzeniu).',
                    'boldtext' => '<a href=' . URL . 'userpanel/loginPanel>LINK </a>'));

            if ($show == 'registerPanel')
                $this->view->render('userpanel/panel_rejestracji');
            else
                array_push($this->view->info, array('type' => 'info', 'text' => 'Nie mam konta utwórz nowe. ',
                    'boldtext' => '<a href=' . URL . 'userpanel/registerPanel>LINK </a>'));
        }
        array_push($this->view->info, array('type' => 'warning', 'text' => 'Każde urządzenie musi być zarejestrowane osobno, nie wolno '
            . 'rejestrować 2 razy tego samego urządzenia, w razie problemów zgłosić się do administratora pokój 401.'));
        
        
        $this->view->render('userpanel/info');
        $this->view->render('footer');
    }

    /**
     * page printed when user is banned
     */
    function banned() {
        $this->view->title = 'Jesteś zbanowany';
        $this->view->render('header');
        array_push($this->view->info, array('type' => 'danger', 'boldtext' => 'Zostałeś zablokowany przez administratora. ',
            'text' => 'Skontaktuj się w sprawie wyjaśnień.'));
        $this->view->render('userpanel/info');
        $this->view->render('footer');
    }

    /**
     * Page when user send POST register Data
     * @throws ArrayException when user register data bad print page with error infoabout it
     */
    function register() {
        $this->view->title = 'Rejestracja';
        $this->view->render('header');

        //jeżeli są dane post
        if (isset($_POST['login'])) {
            try {
                $form = new Form();
                //wrzuca do $form dane z POST i wymusza na nich walidację
                $form->post('imie')
                        ->val('Imię jest zbyt krótkie.', 'minlength', 3)
                        ->val('Imię jest zbyt długie.', 'maxlength', 30)
                        ->post('haslo')
                        ->val('Podane hasła różnią się od siebie.', 'theSame', $_POST['haslo2'])
                        ->val('Hasło jest zbyt krótkie.', 'minlength', 3)
                        ->val('Hasło jest zbyt długie.', 'maxlength', 30)
                        ->post('login')
                        ->val('Login jest zbyt krótki.', 'minlength', 3)
                        ->val('Login jest zbyt długi.', 'maxlength', 30)
                        ->post('nazwisko')
                        ->val('Nazwisko jest zbyt krótkie.', 'minlength', 3)
                        ->val('Nazwisko jest zbyt długie.', 'maxlength', 30)
                        ->post('pokoj')
                        ->val('Numer pokoju nie jest cyfrą.', 'digit')
                        ->val('Podany pokój nie istnieje.', 'existRoomNr')
                        ->post('wydzial')
                        ->val('Nazwa wydziału jest zbyt krótka', 'minlength', 3)
                        ->val('Nazwa wydziału jest zbyt długa', 'maxlength', 300)
                        ->post('kierunek')
                        ->val('Kierunek jest zbyt krótki.', 'minlength', 3)
                        ->val('Kierunek jest zbyt długi.', 'maxlength', 300);
                $form->submit();

                //pobiera tablicę asocjacyjną ze zwalidowanymi danymi POST
                //lub rzuca wyjątkiem Array Exception
                $data = $form->fetch();

                //czy podany login jest zajęty
                $userID = $this->model->getUserID($data['login']);
                if ($userID) {
                    throw new ArrayException(array('Podany login jest już zajęty.'));
                }

                //dopisuje do tablicy
                $data['stan'] = 0;
                $data['datarejestracji'] = @strtotime("now");
                $data['haslo'] = md5($data['haslo']);
                $data['oplata'] = 0;
                $data['datawaznoscikonta'] = $data['datarejestracji'] + 60 * 60 * 24 * 90;
                $data['portyonof'] = 0;
                $data['porty'] = 0;
                $data['downloadhttp'] = 0;
                $data['downloadall'] = 0;
                $data['upload'] = 0;

                //dodaje usera do bazy
                //nie ma errorów bo były rzucane wyjątki w tych przypadkach
                $regNewUser = $this->model->registerNewUser($data);
                if ($regNewUser !== true) {
                    throw new ArrayException(array('Pojawił się problem z bazą danych. Skontaktuj się z administratorem.'));
                }

                //loguje usera
                $newUserID = $this->model->getUserID($data['login']);
                $this->userAuth->logInUser($newUserID, $data['haslo']);

                Header("Location: " . URL . "userpanel");
            } catch (ArrayException $e) {
                //pobiera listę errorów walidacji danych
                $errors = $e->getErrorArray();
                
                //wyświetla ładnie errory
                foreach ($errors as $error) {
                    array_push($this->view->info, array('type' => 'danger', 'text' => $error));
                }

                //info o przerwaniu rejestracji i logowaniu
                array_push($this->view->info, array('type' => 'info', 'text' => 'Mam już konto zaloguj (rejestrowałem się na innym urządzeniu).',
                    'boldtext' => '<a href=' . URL . 'userpanel/loginPanel>LINK </a>'));

                $this->view->render('userpanel/info');
                $this->view->info = array();
                $this->view->render('userpanel/panel_rejestracji');
            }
            $this->view->render('userpanel/info');
            $this->view->render('footer');
        }
    }

}
