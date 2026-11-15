<?php
class Database {
    protected $conn;

    public function __construct() {
        $_ENV = parse_ini_file(__DIR__ . "/../.env");
        $host = $_ENV['HOST'];
        $port = 10683; // e.g., 12345
        $db = $_ENV['DB'];
        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];

        $caCertPath = __DIR__ . '/ca.pem';
        
        $this->conn = mysqli_init(); // enable ssl
        // private key, public cert, ca cert file, ca path, ssl cipher
        $this->conn->ssl_set(NULL, NULL, $caCertPath, NULL, NULL);
        
        // NULL is socket
        $this->conn->real_connect($host, $user, $pass, $db, (int)$port, NULL, MYSQLI_CLIENT_SSL);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>