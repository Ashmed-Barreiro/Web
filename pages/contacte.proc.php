<?php
// Obrir la base de dades existent
$db = new SQLite3('../db/formularis.db');

// Comprovar si el formulari s'ha enviat per POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nombre"];
    $email = $_POST["email"];
    $telefon = $_POST["telefon"];
    $assumpte = $_POST["asunto"];
    $missatge = $_POST["mensaje"];

    // Preparar la consulta
    $stmt = $db->prepare("INSERT INTO contacte (nom, email, telefon, assumpte, missatge) 
                          VALUES (:nom, :email, :telefon, :assumpte, :missatge)");

    $stmt->bindValue(':nom', $nom, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':telefon', $telefon, SQLITE3_TEXT);
    $stmt->bindValue(':assumpte', $assumpte, SQLITE3_TEXT);
    $stmt->bindValue(':missatge', $missatge, SQLITE3_TEXT);

    // Executar
    if ($stmt->execute()) {
        echo "<script>
        alert('✅ Formulari guardat correctament!');
        window.location.href = '../index.html';
    </script>";
    } else {
        echo "<p>❌ Error en guardar el formulari.</p>";
    }
} else {
    echo "<p>❌ El formulari no ha estat enviat correctament.</p>";
}

?>
