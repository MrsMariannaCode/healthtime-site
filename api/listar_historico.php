<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/../healthtime_site.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS alertas_recebidos (id INTEGER PRIMARY KEY AUTOINCREMENT, bpm INTEGER, spo2 INTEGER, tipo_evento TEXT, descricao TEXT, data_hora TEXT)");
    echo json_encode($pdo->query("SELECT * FROM alertas_recebidos ORDER BY id DESC LIMIT 15")->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) { echo json_encode([]); }
?>