<?php
header("Content-Type: application/json");

require_once '../../vendor/autoload.php';
include_once '../../backend/connection.php';
include_once '../../backend/models/user.php';

try {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
    $contacto = isset($_POST['contacto']) ? trim($_POST['contacto']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : null;
    $isAdmin = isset($_POST['isAdmin']) && $_POST['isAdmin'] === 'true' ? 1 : 0;

    $isAdmin = ($isAdmin == 1) ? 1 : 0; // Garantir que isAdmin é 0 ou 1
    // Verificar se todos os campos obrigatórios foram preenchidos
    if (!$nome || !$contacto || !$email || !$password || !$confirmPassword) {
        http_response_code(400);
        echo json_encode(["error" => "Todos os campos são obrigatórios."]);
        exit;
    }

    // Verificar se as senhas coincidem
    if ($password !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(["error" => "As senhas não coincidem."]);
        exit;
    }

    if (!$connection) throw new Exception("Erro na conexão com o banco de dados.");

    // Verificar se já existe um usuário com este email
    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Este email já está em uso."]);
        exit;
    }

    // Inserir novo usuário
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $connection->prepare("INSERT INTO users (nome, contacto, email, password, isAdmin, created_at, expires_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssssi", $nome, $contacto, $email, $hashedPassword, $isAdmin);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Usuário registrado com sucesso."]);
    } else {
        throw new Exception("Erro ao registrar usuário.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
