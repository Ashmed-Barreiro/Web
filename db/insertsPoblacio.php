<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Em connecto a la base de dades
$db = new SQLite3('formularis.db',);

$info = [];
$municipis = [];

// Obro i carrego dades del primer .csv
if (($handle = fopen('../data/mpiscatalunya.csv', 'r')) !== false) {
    fgetcsv($handle); // Em salto la primera línia (nom columnes)
    fgetcsv($handle); // Em salto la primera línia (nom columnes)
    fgetcsv($handle); // Em salto la primera línia (nom columnes)
    fgetcsv($handle); // Em salto la primera línia (nom columnes)

    // [0] cod_municipi, [1] nom_municipi, [2] codi_comarca, [3] nom_comarca
    while (($row = fgetcsv($handle, 0, ',')) !== false) {

        // Saltar files buides o incompletes
        if (count($row) < 4 || empty(trim($row[0]))) continue;

        // agafo dades del fitxer i el guardo a variables
        $codi_municipi = intval($row[0]);
        $nom_municipi = $row[1];
        $codi_comarca = intval($row[2]);
        $comarca = $row[3];


        // Guardar per codi de municipi
        $info[$nom_municipi] = [
            'codi_municipi' => $codi_municipi,
            'municipi' => $nom_municipi,
            'comarca' => $comarca,
            'codi_comarca' => $codi_comarca
        ];
    }
    fclose($handle);
}


// Obro i carrego dades del segon .csv
if (($handle = fopen('../data/pmh446mun_2023_24.csv', 'r')) !== false) {
    fgetcsv($handle); // Saltar capçalera

    //[0] => any, [1] => municipi, [2] => sexe, [3] => concepte, [4] => estat, [5] => valor
    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        // Saltar files buides o incompletes
        if (count($row) < 6 || empty(trim($row[0]))) continue;

        if($row[1] != "Medinyà" && $row[1] != "Catalunya") {
            if($row[2] == "total") {
            $any = intval($row[0]);
            $municipi = $row[1];
            $valor = intval($row[5]);

            $municipis[] = [
                'any' => $any,
                'municipi' => $municipi,
                'codi_municipi' => $info[$municipi]['codi_municipi'],
                'poblacio' => $valor,
                'comarca' => $info[$municipi]['comarca'],
                'codi_comarca' => $info[$municipi]['codi_comarca']
            ];
        }
        }
        
    }
    fclose($handle);
}

//var_dump($municipis[0]);

foreach ($municipis as $municipi) {

    // preparo la consulta
    $stmt = $db->prepare("INSERT INTO poblacio
    (any, municipi, codi_municipi, poblacio, codi_comarca, comarca)
    VALUES (:any, :municipi, :codi_municipi, :poblacio, :codi_comarca, :comarca)");

    // Assignar els valors a cada paràmetre
    $stmt->bindValue(':any', $municipi['any'], SQLITE3_INTEGER);
    $stmt->bindValue(':municipi', $municipi['municipi'], SQLITE3_TEXT);
    $stmt->bindValue(':codi_municipi', $municipi['codi_municipi'], SQLITE3_INTEGER);
    $stmt->bindValue(':poblacio', $municipi['poblacio'], SQLITE3_INTEGER);
    $stmt->bindValue(':codi_comarca', $municipi['codi_comarca'], SQLITE3_INTEGER);
    $stmt->bindValue(':comarca', $municipi['comarca'], SQLITE3_TEXT);

    // Executar la inserció
    $stmt->execute();

}
