<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ...existing code...

    $db = new SQLite3('../db/formularis.db');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if(isset($_GET['comarca'])) {
            $municipis = [];
            $stmt = $db->prepare("SELECT * FROM poblacio WHERE codi_comarca = :codi_comarca");
            $stmt->bindValue(":codi_comarca", $_GET['comarca'], SQLITE3_INTEGER);
            $resultats = $stmt->execute();

           while ($row = $resultats->fetchArray(SQLITE3_ASSOC)) {
                $municipis[] = $row;
            }

            header('Content-Type: application/json');
            echo json_encode($municipis);
        } else {
            header('Content-Type: application/json');
            echo json_encode([ 'code' => 504, 'message' => 'Bad Request' ]);
        }
    } else {
        echo "Error";
    }

?>