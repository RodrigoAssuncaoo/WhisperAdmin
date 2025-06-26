<?php
require_once '../../backend/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método inválido.");
    }

    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("ID da avaliação não fornecido.");
    }

    $id = (int) $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM avaliacoes WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: tables/avaliacoestable.php?sucesso=Avaliação eliminada com sucesso");
    exit;

} catch (Exception $e) {
    header("Location: tables/avaliacoestable.php?erro=" . urlencode($e->getMessage()));
    exit;
}
?>
