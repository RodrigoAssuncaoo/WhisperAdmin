<?php
require_once '../../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $id_tipo_roteiro = intval($_POST['id_tipo_roteiro'] ?? 0);

    if (empty($nome) || $id_tipo_roteiro <= 0) {
        header("Location: pages/roteiros.php?erro=Preenche todos os campos corretamente.");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO roteiros (id_tipo_roteiro, nome) VALUES (?, ?)");
        $stmt->execute([$id_tipo_roteiro, $nome]);
        header("Location: pages/roteiros.php?sucesso=Roteiro adicionado com sucesso.");
    } catch (PDOException $e) {
        header("Location: pages/roteiros.php?erro=Erro ao adicionar roteiro.");
    }
}
