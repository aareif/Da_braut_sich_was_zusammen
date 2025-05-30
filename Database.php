<?php
class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    // Konstruktor zur Initialisirung von gegebenen Datenbankparameter 
    public function __construct()
    {
        $this->servername = 'localhost';
        $this->username = 'DAN';
        $this->password = 'nilspeterpaul';
        $this->dbname = 'Da_braut_sich_was_zusammen';
    }

    // Konstruktor zur Initialisierung der Datenbankparameter
   /** public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    } */

    // Funktion zum Herstellen der Verbindung
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // Funktion zum Trennen der Verbindung
    public function disconnect() {
        $this->conn->close();
        //echo "Verbindung getrennt.<br>";
    }
}
?>
