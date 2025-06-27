<?php
// public/admin/atualizar_roteiro.php

// 1) Carrega a conexão PDO de WhisperAdmin/backend/db.php
//    (parte de public/admin → public → WhisperAdmin → backend/db.php)
$dbFile = realpath(__DIR__ . '/../../backend/db.php');
if (! $dbFile || ! file_exists($dbFile)) {
    die("Não consegui encontrar o arquivo de conexão em: $dbFile");
}
require_once $dbFile;

// 2) Processa apenas requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tables/roteirostable.php');
    exit;
}

// 3) Recebe e valida os dados do formulário (sem editar pontos)
$id      = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome    = trim($_POST['nome'] ?? '');
$id_tipo = filter_input(INPUT_POST, 'id_tipo_roteiro', FILTER_VALIDATE_INT);

if (! $id || $nome === '' || ! $id_tipo) {
    header('Location: tables/roteirostable.php?erro=Dados+inválidos');
    exit;
}

try {
    // 4) Atualiza apenas nome e tipo de roteiro
    $stmt = $pdo->prepare(
        "UPDATE roteiros
           SET nome = ?, id_tipo_roteiro = ?
         WHERE id = ?"
    );
    $stmt->execute([$nome, $id_tipo, $id]);

    // 5) Redireciona de volta para a lista com mensagem de sucesso
    header('Location: tables/roteirostable.php?sucesso=Roteiro+atualizado+com+sucesso');
    exit;

} catch (PDOException $e) {
    // Em produção, prefira logar o erro em vez de exibir os detalhes
    echo '<div class="alert alert-danger">'
       . 'Erro ao atualizar o roteiro: '
       . htmlspecialchars($e->getMessage())
       . '</div>';
    exit;
}
