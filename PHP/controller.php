<?php
include "./model.php";

class Controller
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function avvia()
    {
        if (!isset($_GET['action'])) {
            echo json_encode(["ERRORE" => "Action non specificato"]);
        } else {
            switch ($_GET['action']) {
                case 'login':
                    $this->login();
                    break;
                case 'register':
                    $this->register();
                    break;
                case 'addMessage':
                    $this->addMessage();
                    break;
                case 'getMessages':
                    $this->getMessages();
                    break;
                case 'getUsers':
                    $this->getUsers();
                    break;
                default:
                    echo json_encode(["error" => "Invalid action"]);
                    break;
            }
        }
    }

    private function login()
    {
        $username = $_GET['username'];
        $password = $_GET['password'];
        $user = $this->model->getUser($username, $password);

        if ($user)
            echo json_encode($user);
        else
            echo json_encode(array());
    }

    private function register()
    {
        $username = $_GET['username'];
        $password = $_GET['password'];
        $user = $this->model->addUser($username, $password);

        if ($user)
            echo json_encode(["success" => "Registrazione avvenuta con successo!!!"]);
        else
            echo json_encode(["error" => "Errore nella registrazione!!!"]);
    }

    private function addMessage()
    {
        $mittente_id = $_GET['mittente_id'];
        $destinatario_id = $_GET['destinatario_id'];
        $messaggio = $_GET['messaggio'];

        if ($this->model->addMessage($mittente_id, $destinatario_id, $messaggio))
            echo json_encode(["success" => "Message sent"]);
        else
            echo json_encode(["error" => "Failed to send message"]);
    }

    private function getMessages()
    {
        $mittente_id = $_GET['mittente_id'];
        $destinatario_id = $_GET['destinatario_id'];
        $messages = $this->model->getMessages($mittente_id, $destinatario_id);

        echo json_encode($messages);
    }

    private function getUsers()
    {
        $users = $this->model->getUsers();
        echo json_encode($users);
    }
}

$model = new Model();
$controller = new Controller($model);
$controller->avvia();