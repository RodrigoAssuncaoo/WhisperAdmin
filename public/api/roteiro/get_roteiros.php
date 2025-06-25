<?php
header("Content-Type: application/json");
require_once '../../../vendor/autoload.php';
include_once '../../../backend/db.php';
include_once '../../../backend/auth.php';


try {

    // Validação do token JWT
    verificarToken($pdo);

    $stmt = $pdo->prepare("
        SELECT 
            r.id,
            r.nome,
            r.id_tipo_roteiro AS id_tipo,
            t.nome AS tipo,
            r.picpath,
            ROUND(AVG(a.avaliacao_roteiro), 2) AS media_score
        FROM roteiros r
        JOIN tipo_roteiroS t ON r.id_tipo_roteiro = t.id
        JOIN avaliacoes a ON r.id = a.id_roteiro
        GROUP BY r.id, r.nome, r.picPath
    ");
    $stmt->execute();

    $roteiros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Roteiros obtidos com sucesso',
        'data' => [
            'roteiros' => $roteiros
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
