<?php

include_once "./Database.php";

class KaryawanController {

    private $conn; 


    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getLevel() {
        $sql = 
        "SELECT  * FROM level ";
        $result = $this->conn->query($sql);
        $level_list = array();

        while($row = $result->fetch_assoc()) {
            $level_list[] = $row;
        }
        return json_encode($level_list);
    }

    public function getJabatan() {
        $sql = 
        "SELECT  * FROM jabatan ";
        $result = $this->conn->query($sql);
        $jabatan_list = array();

        while($row = $result->fetch_assoc()) {
            $jabatan_list[] = $row;
        }
        return json_encode($jabatan_list);
    }

    public function getKaryawan () {
        $sql = 
        "SELECT  
            karyawan.id_karyawan,
            karyawan.nama_karyawan,
            karyawan.nik,
            level.nama_level,
            jabatan.nama_jabatan
        FROM 
            karyawan 
        INNER JOIN
            jabatan on karyawan.id_jabatan = jabatan.id_jabatan
        INNER JOIN 
            level on level.id_level = karyawan.id_level
        ";
        $result = $this->conn->query($sql);
        $karyawan_list = array();

        while($row = $result->fetch_assoc()) {
            $karyawan_list[] = $row;
        }
        return json_encode($karyawan_list);
    }

    public function updateKaryawan($id_karyawan, $data) {
        $id_level = (int)$data['id_level'];

        // Membuat query SQL
        $sql = "UPDATE karyawan 
                SET nama_karyawan = '{$data['nama']}', 
                    nik = '{$data['nik']}', 
                    id_level = $id_level, 
                    id_jabatan = '{$data['id_jabatan']}'
                WHERE id_karyawan = '$id_karyawan'";

        // Menjalankan query
        if ($this->conn->query($sql) === TRUE) {
            return json_encode(["message" => "Karyawan berhasil diupdate"]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Gagal mengupdate karyawan: " . $this->conn->error]);
        }
    }

    public function getKaryawanById ($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = 
        "SELECT 
            karyawan.id_karyawan,
            karyawan.nama_karyawan as nama,
            karyawan.nik,
            level.nama_level,
            karyawan.id_level,
            karyawan.id_jabatan,
            jabatan.nama_jabatan
        FROM 
            karyawan 
        INNER JOIN
            jabatan on karyawan.id_jabatan = jabatan.id_jabatan
        INNER JOIN 
            level on level.id_level = karyawan.id_level
        WHERE id_karyawan = '$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0 ) {
            return json_encode($result->fetch_assoc());
        } else {
            return json_encode(["error" => "Karyawan tidak ditemukan!"]);
        }
    }

    public function isIdKaryawanExists($id_karyawan) {
        $query = "SELECT COUNT(*) FROM karyawan WHERE id_karyawan = ?";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error); 
        }
        
        $stmt->bind_param('s', $id_karyawan);
        $stmt->execute();
        
        $stmt->bind_result($count);
        $stmt->fetch();
        
        $stmt->close();
        
        return $count > 0;
    }
    

    public function addKaryawan ($data)  {

        $nama = $this->conn->real_escape_string($data['nama']);
        $id_karyawan = $this->conn->real_escape_string($data['id_karyawan']);
        $nik = $this->conn->real_escape_string($data['nik']);
        $id_level = $this->conn->real_escape_string($data['id_level']);
        $id_jabatan = $this->conn->real_escape_string($data['id_jabatan']);

        $query = "INSERT INTO karyawan (id_karyawan, nama_karyawan, nik, id_level, id_jabatan) 
            VALUES ('$id_karyawan', '$nama', '$nik', $id_level ,'$id_jabatan')";
    
        if ($this->conn->query($query) === TRUE) {
            return json_encode(["message" => "Karyawan berhasil ditambahkan"]);
        } else {
            return json_encode(["message" => "Error: " . $this->conn->error]);
        }

    }

    public function deleteKaryawan($id) {       
        $query = "DELETE FROM karyawan WHERE id_karyawan = '$id'";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute()) {
            return json_encode(["message" => "Karyawan berhasil dihapus"]);
        } else {
            return json_encode(["message" => "Gagal menghapus karyawan"]);
        }
    }
}