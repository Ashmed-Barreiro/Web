<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $db = new SQLite3('../db/formularis.db');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if(isset($_GET['codi_municipi'])) {
            $municipi = [];
            $stmt = $db->prepare("SELECT * FROM poblacio WHERE codi_municipi = :codi_municipi");
            $stmt->bindValue(":codi_municipi", $_GET['id'], SQLITE3_INTEGER);
            $resultats = $stmt->execute();

            $municipi = $resultats->fetchArray(SQLITE3_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($municipi);
        } else {
            
            $municipis = [];
            $resultats = $db->query("SELECT * FROM poblacio");
            while ($fila = $resultats->fetchArray(SQLITE3_ASSOC)) {
                $municipis[] = $fila;
            }

            header('Content-Type: application/json');
            echo json_encode($municipis);
        }
    } else {
        echo "Error";
    }

?>