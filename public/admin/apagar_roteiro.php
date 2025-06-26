<?php
require_once '../../backend/db.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: tables/roteirostable.php?erro=ID inválido.");
    exit;
}

try {
    // Eliminar pontos associados primeiro (foreign key)
    $pdo->prepare("DELETE FROM roteiro_pontos WHERE id_roteiro = ?")->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM roteiros WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount()) {
        header("Location: tables/roteirostable.php?sucesso=Roteiro apagado com sucesso.");
        exit;
    } else {
        header("Location: tables/roteirostable.php?erro=Roteiro não encontrado.");
        exit;
    }
} catch (PDOException $e) {
    header("Location: tables/roteirostable.php?erro=Erro ao apagar o roteiro.");
    exit;
}
