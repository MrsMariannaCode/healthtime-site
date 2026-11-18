<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
date_default_timezone_set('America/Bahia');

try {
    // Banco SQLite local do site médico 
    $pdo = new PDO("sqlite:" . __DIR__ . "/../healthtime_site.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS pareceres_medicos (id INTEGER PRIMARY KEY AUTOINCREMENT, texto TEXT, data_hora TEXT)");

    $input = json_decode(file_get_contents('php://input'), true);
    if ($input && !empty($input['texto'])) {
        $stmt = $pdo->prepare("INSERT INTO pareceres_medicos (texto, data_hora) VALUES (?, ?)");
        $stmt->execute([$input['texto'], date('d/m/Y H:i:s')]);

        // Envio para o App
        $urlApp = "https://healthtime-app.onrender.com/api/receber_alerta.php";
        
        @file_get_contents($urlApp, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode(['texto' => $input['texto']]),
                'timeout' => 3
            ]
        ]));

        echo json_encode(['status' => 'sucesso']);
    }
} catch (Exception $e) { 
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]); 
}
?>