<?php
require_once '../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $id_tipo_roteiro = intval($_POST['id_tipo_roteiro'] ?? 0);
    $pontos = $_POST['pontos'] ?? [];

    // Validação
    if (empty($nome) || $id_tipo_roteiro <= 0 || empty($pontos)) {
        header("Location: tables/roteirostable.php?erro=Preenche todos os campos e seleciona os pontos.");
        exit;
    }

    try {
        // Começar transação
        $pdo->beginTransaction();

        // Inserir o roteiro
        $stmt = $pdo->prepare("INSERT INTO roteiros (id_tipo_roteiro, nome) VALUES (?, ?)");
        $stmt->execute([$id_tipo_roteiro, $nome]);
        $idRoteiro = $pdo->lastInsertId();

        // Inserir pontos associados ao roteiro
        $stmtPonto = $pdo->prepare("INSERT INTO roteiro_pontos (id_roteiro, id_ponto, ordem) VALUES (?, ?, ?)");
        $ordem = 1;
        foreach ($pontos as $pontoId) {
            $stmtPonto->execute([$idRoteiro, $pontoId, $ordem]);
            $ordem++;
        }

        $pdo->commit();
        header("Location: tables/roteirostable.php?sucesso=Roteiro adicionado com sucesso.");
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        header("Location: tables/roteirostable.php?erro=Erro ao adicionar roteiro.");
        exit;
    }
}
