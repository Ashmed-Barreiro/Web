<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Em connecto a la base de dades
$db = new SQLite3('formularis.db',);

$infoConsum = [];

//consumeixo l'API pública
$url= 'https://analisi.transparenciacatalunya.cat/resource/8idm-becu.json?$select=any,municipi,consum_kwh,CDMUN&$where=any=2023&codi_sector=7';

$resposta = file_get_contents($url);
$data = json_decode($resposta, true);

//var_dump ($data);
if ($data && is_array($data)) {

    // [["any"]=> "2023" ["municipi"]=> "ABRERA" ["consum_kwh"]=> "15370956" ["CDMUN"]=> "08001" 
    $infoConsum = [];

    foreach ($data as $info) {
         $infoConsum[] = [
            'codi_municipi' => intval($info['CDMUN']),
            'any' => $info['any'],
            'tipus' => "energia",
            'unitat' => "kWh",
            'valor' => intval($info['consum_kwh'])
        ];
    }


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


