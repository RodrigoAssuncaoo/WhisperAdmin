<?php
require_once '../../backend/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método inválido.");
    }

    if (
        !isset($_POST['id']) || !isset($_POST['avaliacao_roteiro']) || !isset($_POST['comentario']) ||
        empty($_POST['id']) || empty($_POST['avaliacao_roteiro']) || empty($_POST['comentario'])
    ) {
        throw new Exception("Todos os campos são obrigatórios.");
    }

    $id = (int) $_POST['id'];
    $avaliacao = (int) $_POST['avaliacao_roteiro'];
    $comentario = trim($_POST['comentario']);

    $stmt = $pdo->prepare("UPDATE avaliacoes SET avaliacao_roteiro = ?, comentario = ? WHERE id = ?");
    $stmt->execute([$avaliacao, $comentario, $id]);

    header("Location: tables/avaliacoestable.php?sucesso=Avaliação atualizada com sucesso");
    exit;

} catch (Exception $e) {
    header("Location: tables/avaliacoestable.php?erro=" . urlencode($e->getMessage()));
    exit;
}
?>
