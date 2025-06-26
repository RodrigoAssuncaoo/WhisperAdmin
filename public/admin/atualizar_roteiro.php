<?php
require_once('../../backend/db.php');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Acesso inválido.");
    }

    $id = $_POST['id'] ?? '';
    $nome = trim($_POST['nome'] ?? '');
    $id_tipo_roteiro = $_POST['id_tipo_roteiro'] ?? '';

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID do roteiro inválido.");
    }

    if (!$nome || !$id_tipo_roteiro) {
        throw new Exception("Preenche todos os campos.");
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s]+$/", $nome)) {
        throw new Exception("O nome só pode conter letras, números e espaços.");
    }

    if (!is_numeric($id_tipo_roteiro)) {
        throw new Exception("ID do tipo de roteiro inválido.");
    }

    // Atualiza os dados do roteiro
    $update = $pdo->prepare("UPDATE roteiros SET nome = :nome, id_tipo_roteiro = :id_tipo_roteiro WHERE id = :id");
    $update->execute([
        'nome' => $nome,
        'id_tipo_roteiro' => $id_tipo_roteiro,
        'id' => $id
    ]);

    header("Location: tables/roteirostable.php?sucesso=Roteiro atualizado com sucesso.");
    exit;

} catch (Exception $e) {
    header("Location: tables/roteirostable.php?erro=" . urlencode($e->getMessage()));
    exit;
}
?>
