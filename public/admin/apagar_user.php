<?php
require_once('../../backend/db.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Acesso inválido.");
    }

    if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception("ID de utilizador inválido.");
    }

    $id = (int) $_POST['id'];

    // Verificar se o utilizador existe antes de apagar
    $verifica = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $verifica->execute(['id' => $id]);

    if ($verifica->rowCount() === 0) {
        throw new Exception("Utilizador não encontrado.");
    }

    // Apagar utilizador
    $delete = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $delete->execute(['id' => $id]);

    header("Location: tables/userstable.php?sucesso=Utilizador apagado com sucesso.");
    exit;

} catch (Exception $e) {
    header("Location: tables/userstable.php?erro=" . urlencode($e->getMessage()));
    exit;
}