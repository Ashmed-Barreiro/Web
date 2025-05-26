<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Em connecto a la base de dades
$db = new SQLite3('formularis.db',);

$infoConsum = [];

// Obro i carrego dades del primer .csv
if (($handle = fopen('../data/volum-aigua-consum-municipi-2023.csv', 'r')) !== false) {
    fgetcsv($handle); // Em salto la primera línia (nom columnes)


    //{ [0]=> "Codi Ine", [1]=>"Municipi" [2]=> "Comarca 2023" [3]=> "Domèstic Xarxa" [4]=> "Activitats Econom. i Fonts Pròpies", [5]=> "Total"
    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        //var_dump($row);

        // Saltar files buides o incompletes
        if (count($row) < 6 || empty(trim($row[0]))) continue;

        $any = 2023;
        $codi_municipi = intval($row[0]);
        $valor_domestic = intval($row[3]);

        $infoConsum[] = [
            'codi_municipi' => $codi_municipi,
            'any' => $any,
            'tipus' => "aigua",
            'unitat' => "m³/any",
            'valor' => $valor_domestic
        ];

        //var_dump($infoConsum);
    }

    fclose($handle);


    //faig inserts
    foreach ($infoConsum as $info) {

        // preparo la consulta
        $stmt = $db->prepare("INSERT INTO consum (any, codi_municipi, tipus, unitat, valor) VALUES (:any, :codi_municipi, :tipus, :unitat, :valor)");

        // assigno els valors a cada paràmetre (de manera segura)
        $stmt->bindValue(':any', $info['any'], SQLITE3_INTEGER);
        $stmt->bindValue(':codi_municipi', $info['codi_municipi'], SQLITE3_INTEGER);
        $stmt->bindValue(':tipus', $info['tipus'], SQLITE3_TEXT);
        $stmt->bindValue(':unitat', $info['unitat'], SQLITE3_TEXT);
        $stmt->bindValue(':valor', $info['valor'], SQLITE3_INTEGER);

        // executo la consulta
        $stmt->execute();

    }
    echo 'Inserts fets correctament';

}


