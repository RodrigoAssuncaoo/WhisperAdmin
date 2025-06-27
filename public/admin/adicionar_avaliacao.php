<?php
require_once '../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegando os dados do formulário
    $user_id = intval($_POST['user_id'] ?? 0);  // ID do usuário
    $id_roteiro = intval($_POST['id_roteiro'] ?? 0);  // ID do roteiro
    $avaliacao_roteiro = intval($_POST['avaliacao_roteiro'] ?? 0);  // Nota de avaliação (1-10)
    $comentario = trim($_POST['comentario'] ?? '');  // Comentário da avaliação

    // Validação dos dados
    if (empty($user_id) || empty($id_roteiro) || !is_numeric($avaliacao_roteiro) || $avaliacao_roteiro < 1 || $avaliacao_roteiro > 10 || empty($comentario)) {
        // Mensagem de erro
        echo 'Erro de validação. Verifique os dados e tente novamente.';
        exit;
    }

    try {
        // Inserir a avaliação no banco de dados
        $stmt = $pdo->prepare("
            INSERT INTO avaliacoes (user_id, id_roteiro, avaliacao_roteiro, comentario)
            VALUES (:user_id, :id_roteiro, :avaliacao_roteiro, :comentario)
        ");

        // Executar a query com os valores passados
        $stmt->execute([
            ':user_id' => $user_id,
            ':id_roteiro' => $id_roteiro,
            ':avaliacao_roteiro' => $avaliacao_roteiro,
            ':comentario' => $comentario
        ]);

        // Redirecionar com sucesso
        header("Location: tables/avaliacoestable.php?sucesso=" . urlencode('Avaliação adicionada com sucesso!'));
        exit;

    } catch (PDOException $e) {
        // Caso ocorra erro, redirecionar para a página com erro
        header("Location: tables/avaliacoestable.php?erro=" . urlencode($e->getMessage()));
        exit;
    }
}
?>