<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';
require_once '../sendemail.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "error" => "Método não permitido"
        ]);
        exit;
    }

    $nome = $_POST['nome'] ?? null;
    $contacto = $_POST['contacto'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    if (!$nome || !$contacto || !$email || !$password || !$confirmPassword) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "error" => "Todos os campos são obrigatórios."
        ]);
        exit;
    }

    if ($password !== $confirmPassword) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "error" => "As senhas não coincidem."
        ]);
        exit;
    }

    if (!$connection) throw new Exception("Erro na conexão com o banco de dados.");

    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode([
            "success" => false,
            "error" => "Este email já está em uso."
        ]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(16));
    $role = 3;

    $stmt = $connection->prepare("INSERT INTO users (nome, contacto, email, password, role, token, created_at, expires_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssssss", $nome, $contacto, $email, $hashedPassword, $role, $token);

    if ($stmt->execute()) {
        sendVerificationEmail($email, $token, $nome);
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Utilizador registado com sucesso"
        ]);
    } else {
        throw new Exception("Erro ao registar utilizador.");
    }
    } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
