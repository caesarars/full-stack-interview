<?php
class Database {
    private $servername = "mysql";  // Nama service MySQL dari docker-compose.yml
    private $username = "user";     // Username MySQL
    private $password = "password"; // Password MySQL
    private $dbname = "mydatabase";   // Nama database
    public $conn;

    // Fungsi untuk membuat koneksi ke MySQL
    public function getConnection() {
        // Hanya buat koneksi jika belum ada
        if ($this->conn == null) {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

            // Cek apakah koneksi berhasil
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }

        return $this->conn;
    }

    // Fungsi untuk menutup koneksi (opsional)
    public function closeConnection() {
        if ($this->conn != null) {
            $this->conn->close();
        }
    }
}
?>
