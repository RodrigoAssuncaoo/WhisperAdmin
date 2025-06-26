<?php
require_once('../../backend/db.php');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Acesso inválido.");
    }

    $id = $_POST['id'] ?? '';
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $role = $_POST['role'] ?? '';

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido.");
    }

    if (!$nome || !$email || !$contacto || !$role) {
        throw new Exception("Preenche todos os campos.");
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nome)) {
        throw new Exception("O nome só pode conter letras e espaços.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email inválido.");
    }

    if (!preg_match("/^\d{9}$/", $contacto)) {
        throw new Exception("O contacto deve ter exatamente 9 dígitos.");
    }

    if (!in_array($role, ['1', '2', '3'])) {
        throw new Exception("Função inválida.");
    }

    // Verifica se o email já existe para outro utilizador
    $verifica = $pdo->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(:email) AND id != :id");
    $verifica->execute(['email' => $email, 'id' => $id]);
    if ($verifica->rowCount() > 0) {
        throw new Exception("Este email já pertence a outro utilizador.");
    }

    // Atualiza os dados
    $update = $pdo->prepare("UPDATE users SET nome = :nome, email = :email, contacto = :contacto, role = :role WHERE id = :id");
    $update->execute([
        'nome' => $nome,
        'email' => $email,
        'contacto' => $contacto,
        'role' => $role,
        'id' => $id
    ]);

    header("Location: tables/userstable.php?sucesso=Utilizador atualizado com sucesso.");
    exit;

} catch (Exception $e) {
    header("Location: tables/userstable.php?erro=Erro ao atualizar o utilizador: ");
    exit;
}