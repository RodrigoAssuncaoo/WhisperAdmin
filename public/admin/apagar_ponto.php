<?php
require_once '../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        header("Location: tables/pontostable.php?erro=ID inválido");
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM pontos WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: tables/pontostable.php?sucesso=Ponto eliminado com sucesso.");
        exit;
    } catch (PDOException $e) {
        header("Location: tables/pontostable.php?erro=" . urlencode($e->getMessage()));
        exit;
    }
}
