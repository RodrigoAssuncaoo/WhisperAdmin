<?php
require_once '../../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id'] ?? 0);  // ID do usuário
    $id_roteiro = intval($_POST['id_roteiro'] ?? 0);  // ID do roteiro
    $avaliacao_roteiro = intval($_POST['avaliacao_roteiro'] ?? 0);  // Nota de avaliação (1-10)
    $comentario = trim($_POST['comentario'] ?? '');  // Comentário da avaliação

    // Validação simples
    if (empty($user_id) || empty($id_roteiro) || !is_numeric($avaliacao_roteiro) || $avaliacao_roteiro < 1 || $avaliacao_roteiro > 10 || empty($comentario)) {
        echo 'Erro de validação. Verifique os dados e tente novamente.';
        exit;
    }

    try {
        // Inserir a avaliação no banco de dados
        $stmt = $pdo->prepare("
            INSERT INTO avaliacoes (user_id, id_roteiro, avaliacao_roteiro, comentario)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $id_roteiro, $avaliacao_roteiro, $comentario]);

        // Redirecionar com sucesso
        header("Location: tables/avaliacoestable.php?sucesso=Avaliação adicionada com sucesso!");
        exit;

    } catch (PDOException $e) {
        // Caso ocorra erro, redirecionar para a página com erro
        header("Location: tables/avaliacoestable.php?erro=" . urlencode($e->getMessage()));
        exit;
    }
}
?>
