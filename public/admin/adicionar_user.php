<?php
require_once('../../backend/db.php');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Acesso inválido.");
    }

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? '';

    if (!$nome || !$email || !$contacto || !$password || !$role) {
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

    if (strlen($password) < 6) {
        throw new Exception("A palavra-passe deve ter pelo menos 6 caracteres.");
    }

    if (!in_array($role, ['1', '2', '3'])) {
        throw new Exception("Função inválida.");
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(:email)");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        throw new Exception("Email já existe.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (nome, email, contacto, password, role) VALUES (:nome, :email, :contacto, :password, :role)");
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'contacto' => $contacto,
        'password' => $hashedPassword,
        'role' => $role
    ]);

    // 🔁 Redirecionar para a tabela após adicionar
    header("Location: tables/userstable.php?sucesso=1");
    exit;

} catch (Exception $e) {
    // Podes redirecionar com o erro também se quiseres mostrar
    header("Location: tables/userstable.php?erro=" . urlencode($e->getMessage()));
    exit;
}
