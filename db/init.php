<?php
// Crear o obrir la base de dades SQLite
$db = new SQLite3('formularis.db');

// Crear la taula si no existeix
$db->exec("CREATE TABLE IF NOT EXISTS contacte (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    email TEXT NOT NULL,
    telefon TEXT,
    assumpte TEXT NOT NULL,
    missatge TEXT NOT NULL,
    data_env DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Crear la taula 'poblacio' si no existeix
$db->exec("CREATE TABLE IF NOT EXISTS poblacio (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    any INTEGER NOT NULL,
    municipi TEXT NOT NULL,
    codi_municipi integer NOT NULL,
    poblacio INTEGER NOT NULL,
    codi_comarca integer NOT NULL,
    comarca TEXT NOT NULL
)");

echo "<p>✅ Taula 'poblacio' creada correctament a formularis.db</p>";
?>


