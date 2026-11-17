<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
date_default_timezone_set('America/Bahia');
try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/../healthtime_site.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS alertas_recebidos (id INTEGER PRIMARY KEY AUTOINCREMENT, bpm INTEGER, spo2 INTEGER, tipo_evento TEXT, descricao TEXT, data_hora TEXT)");
    
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input) {
        $stmt = $pdo->prepare("INSERT INTO alertas_recebidos (bpm, spo2, tipo_evento, descricao, data_hora) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$input['bpm'], $input['spo2'], $input['tipo_evento'], $input['descricao'], $input['data_hora'] ?? date('d/m/Y H:i:s')]);
        echo json_encode(['status' => 'sucesso']);
    }
} catch (Exception $e) { echo json_encode(['status' => 'erro']); }
?>