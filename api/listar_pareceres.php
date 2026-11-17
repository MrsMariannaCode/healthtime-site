<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/../healthtime_site.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS pareceres_medicos (id INTEGER PRIMARY KEY AUTOINCREMENT, texto TEXT, data_hora TEXT)");
    echo json_encode($pdo->query("SELECT * FROM pareceres_medicos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) { echo json_encode([]); }
?>