<?php
$any = 2023;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$db = new SQLite3('../db/formularis.db');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['codi_municipi'])) {
        $municipi = [];
        
        if(isset($_GET['tipus']) && ($_GET['tipus'] == 'aigua' || $_GET['tipus'] == 'energia')) { //TODO: afegir residus
            $stmt = $db->prepare("SELECT * FROM consum WHERE tipus = :tipus");
            $stmt->bindValue(":codi_municipi", $_GET['codi_municipi'], SQLITE3_INTEGER);
            $stmt->bindValue(":tipus", $_GET['tipus'], SQLITE3_TEXT);
            $resultats = $stmt->execute();

            $municipi = $resultats->fetchArray(SQLITE3_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($municipi);
        } else {
            $stmt = $db->prepare("SELECT * FROM consum WHERE codi_municipi = :codi_municipi");
            $stmt->bindValue(":codi_municipi", $_GET['codi_municipi'], SQLITE3_INTEGER);
            $resultats = $stmt->execute();

             while ($fila = $resultats->fetchArray(SQLITE3_ASSOC)) {
                $municipi[] = $fila;
            }

            header('Content-Type: application/json');
            echo json_encode($municipi);
        }
      

    } else {
        
        $municipis = [];
        $resultats = $db->query("SELECT * FROM consum WHERE any = $any");
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