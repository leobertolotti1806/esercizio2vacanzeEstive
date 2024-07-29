<?php
class Model
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "chat_db");

        if ($this->conn->connect_error)
            die("Connessione fallita: " . $this->conn->connect_error);
    }

    public function getUser($username, $password)
    {
        $ris = $this->conn->query("SELECT * FROM utenti WHERE username = '$username'
                 AND password = '$password'");

        return $ris->fetch_assoc();
    }

    public function addUser($username, $password)
    {
        $ris = $this->conn->query("INSERT INTO utenti (username, password) VALUES
        ('$username', '$password')");

        if ($this->conn->connect_error)
            die("Connessione fallita: " . $this->conn->connect_error);

        return $this->conn->connect_error;
    }

    public function getUsers()
    {
        $result = $this->conn->query("SELECT id, username FROM utenti");
        return $result->fetch_all();
    }

    public function addMessage($mittente_id, $destinatario_id, $messaggio)
    {
        $ris = $this->conn->query("INSERT INTO messaggi (mittente_id, destinatario_id, messaggio)
        VALUES ('$mittente_id', '$destinatario_id', '$messaggio')");

        if ($this->conn->connect_error)
            die("Connessione fallita: " . $this->conn->connect_error);

        return $this->conn->connect_error;
    }

    public function getMessages($mittente_id, $destinatario_id)
    {
        $ris = $this->conn->query("SELECT * FROM messaggi WHERE 
        (mittente_id = '$mittente_id' AND destinatario_id = '$destinatario_id') 
        OR (mittente_id = '$destinatario_id' AND destinatario_id = '$mittente_id')
        ORDER BY timestamp");

        return $ris->fetch_all();
    }
}