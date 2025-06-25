<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/db.php';
include_once '../../../backend/auth.php';

try {
    // ✅ Verifica o token JWT
    verificarToken($pdo);

    // ✅ Consulta os dados da tabela tipo_roteiros
    $stmt = $pdo->prepare("
        SELECT 
            id,
            nome AS title,
            picPath
        FROM tipo_roteiros
    ");
    $stmt->execute();

    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Resposta JSON com formato compatível com a app
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Tipos de roteiro obtidos com sucesso',
        'data' => [
            'tipos_roteiros' => $tipos
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
