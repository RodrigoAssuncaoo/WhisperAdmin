<?php
require_once '../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        header("Location: tables/roteirostable.php?erro=ID inválido.");
        exit;
    }

    try {
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
} else {
    // Se alguém tentar aceder por outro método (ex: GET)
    header("Location: tables/roteirostable.php?erro=Método inválido.");
    exit;
}
