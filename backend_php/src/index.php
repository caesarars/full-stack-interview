<?php

include_once "./KaryawanController.php";
include_once "./MainController.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$karyawanController = new KaryawanController();
$mainController = new MainController();

$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0]; 
$requestUri = trim($requestUri, '/');
$method = $_SERVER['REQUEST_METHOD'];
$karyawanController = new KaryawanController();
$mainController = new MainController();


$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0]; 
$requestUri = trim($requestUri, '/');


switch($method) {
    case "OPTIONS":
        // Izinkan semua origins, methods, dan headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        http_response_code(204); // No Content
        exit; 

    case "GET":
        if ($requestUri === 'api/karyawan') {
            echo $karyawanController->getKaryawan();
        }else if ($requestUri === 'api/level') {
            echo $karyawanController->getLevel();
        } else if ($requestUri === 'api/jabatan') {
            echo $karyawanController->getJabatan();
        }  else if (preg_match('/^api\/karyawan\/([0-9a-zA-Z]+)$/', $requestUri, $matches)) {
            $id = $matches[1];
            echo $karyawanController->getKaryawanById($id);
        } else if (preg_match('/^api\/bintang\/([0-9]+)$/', $requestUri, $matches)) {
            $id = $matches[1];
            echo $mainController->bintangSatu($id);
        } else if (preg_match('/^api\/bintang2\/([0-9]+)$/', $requestUri, $matches)) {
            $id = $matches[1];
            echo $mainController->bintangDua($id); 
         } else if (preg_match('/^api\/bintang3\/([0-9]+)$/', $requestUri, $matches)) {
            $id = $matches[1];
            echo $mainController->bintangTiga($id); 
         } else if (preg_match('/^api\/rupiah\/([0-9]+)$/',  $requestUri, $matches)) {
            $value = $matches[1];
            echo $mainController->formatRupiah($value);
         }
         else {
            http_response_code(404);
            echo json_encode(["message" => "Not Found"]);
        }
        break;
    
    case 'POST':
        if ($requestUri === 'api/karyawan/add') {
            $inputData = json_decode(file_get_contents('php://input'), true);
            if (empty($inputData['nama']) || empty($inputData['id_karyawan'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['message' => 'Data tidak valid']);
                exit;
            }

            if (empty($inputData['nik']) || !is_numeric($inputData['nik'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['message' => 'Data tidak valid: NIK harus berupa angka']);
                exit;
            }

            if ($karyawanController->isIdKaryawanExists($inputData['id_karyawan'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['message' => 'Data tidak valid: ID Karyawan sudah ada']);
                exit;
            }

            echo $karyawanController->addKaryawan($inputData);
        }
        break;
     
        case 'PUT':
            if (preg_match('/^api\/karyawan\/([0-9a-zA-Z]+)$/', $requestUri, $matches)) {
                $id_karyawan = $matches[1];
                $inputData = json_decode(file_get_contents('php://input'), true);
        
                if (empty($inputData['nama']) || empty($inputData['nik']) || empty($inputData['id_level']) || empty($inputData['id_jabatan'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['message' => 'Data tidak lengkap']);
                    exit;
                }
        
                echo $karyawanController->updateKaryawan($id_karyawan, $inputData);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Karyawan tidak ditemukan']);
            }
            break;    

    case 'DELETE' : 
        if (preg_match('/^api\/karyawan\/delete\/([0-9a-zA-Z]+)$/', $requestUri, $matches)) {
            $id = $matches[1];
            echo $karyawanController->deleteKaryawan($id);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Not Found"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}

?>
