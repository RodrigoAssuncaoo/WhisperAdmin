<?php
header("Content-Type: application/json");
require_once '../../../vendor/autoload.php';
include_once '../../../backend/db.php';
include_once '../../../backend/auth.php';

try {
    // Verifica o token JWT
    verificarToken($pdo);

    // Confirma se o parâmetro 'id_roteiro' foi enviado
    if (!isset($_GET['id_roteiro'])) {
        throw new Exception("ID do roteiro não fornecido.");
    }

    $idRoteiro = intval($_GET['id_roteiro']);

    // Busca os dados do roteiro
    $stmtRoteiro = $pdo->prepare("
        SELECT 
            r.id,
            r.nome,
            r.id_tipo_roteiro AS id_tipo,
            t.nome AS tipo,
            r.picpath,
            ROUND(AVG(a.avaliacao_roteiro), 2) AS media_score
        FROM roteiros r
        JOIN tipo_roteiros t ON r.id_tipo_roteiro = t.id
        LEFT JOIN avaliacoes a ON r.id = a.id_roteiro
        WHERE r.id = ?
        GROUP BY r.id, r.nome, r.picpath
    ");
    $stmtRoteiro->execute([$idRoteiro]);
    $roteiro = $stmtRoteiro->fetch(PDO::FETCH_ASSOC);

    if (!$roteiro) {
        throw new Exception("Roteiro não encontrado.");
    }

    // Busca apenas os nomes dos pontos do roteiro
    $stmtPontos = $pdo->prepare("
        SELECT p.nome
        FROM roteiro_pontos rp
        JOIN pontos p ON rp.id_ponto = p.id
        WHERE rp.id_roteiro = ?
        ORDER BY rp.id ASC
    ");
    $stmtPontos->execute([$idRoteiro]);
    $nomesPontos = $stmtPontos->fetchAll(PDO::FETCH_COLUMN); // Apenas os nomes

    // Adiciona os nomes dos pontos ao array do roteiro
    $roteiro['pontos'] = $nomesPontos;

    // Resposta final
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Roteiro obtido com sucesso',
        'data' => $roteiro
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
