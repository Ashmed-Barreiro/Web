<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Em connecto a la base de dades
$db = new SQLite3('formularis.db',);

$infoConsum = [];

//consumeixo l'API pública
$url= 'https://analisi.transparenciacatalunya.cat/resource/69zu-w48s.json?$select=codi_municipi,any,r_s_r_m_total&$where=any=%272023%27';

$resposta = file_get_contents($url);
$data = json_decode($resposta, true);

//var_dump ($data);
if ($data && is_array($data)) {

    $infoConsum = [];

    //["codi_municipi"]=> "250019" ["any"]=> "2023" ["r_s_r_m_total"]=> "39.17" 

    foreach ($data as $info) {
        $codi_reduit = intval($info['codi_municipi']/10);

         $infoConsum[] = [
            'codi_municipi' => intval($codi_reduit),
            'any' => 2023,
            'valor' => floatval($info['r_s_r_m_total'])
        ];



        
        /*echo $info['codi_municipi'];
        echo '<br>';*/

    }


//faig inserts
    foreach ($infoConsum as $info) {

        // preparo la consulta
        $stmt = $db->prepare("INSERT INTO consum (any, codi_municipi, tipus, unitat, valor) VALUES (:any, :codi_municipi, :tipus, :unitat, :valor)");

        // assigno els valors a cada paràmetre (de manera segura)
        $stmt->bindValue(':any', $info['any'], SQLITE3_INTEGER);
        $stmt->bindValue(':codi_municipi', $info['codi_municipi'], SQLITE3_INTEGER);
        $stmt->bindValue(':tipus', 'reciclatge', SQLITE3_TEXT);
        $stmt->bindValue(':unitat', '%', SQLITE3_TEXT);
        $stmt->bindValue(':valor', $info['valor'], SQLITE3_FLOAT);

        // executo la consulta
        $stmt->execute();

    }
    echo 'Inserts fets correctament';

}


