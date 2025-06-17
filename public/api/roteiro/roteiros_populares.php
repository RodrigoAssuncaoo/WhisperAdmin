<?php
header("Content-Type: application/json");
require_once '../../../vendor/autoload.php';
include_once '../../../backend/db.php';
include_once '../../../backend/auth.php';

try {
    // Validação do token JWT
    verificarToken($pdo);

    $stmt = $pdo->prepare("
        SELECT r.id, r.nome, r.picPath, 
               ROUND(AVG(a.avaliacao_roteiro), 2) AS media_score
        FROM roteiros r
        JOIN avaliacoes a ON r.id = a.id_roteiro
        GROUP BY r.id, r.nome, r.picPath
        HAVING media_score > 7
        ORDER BY media_score DESC
    ");
    $stmt->execute();

    $roteiros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Roteiros populares obtidos com sucesso',
        'data' => [
            'roteiros_populares' => $roteiros
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
