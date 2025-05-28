<?php
header("Content-Type: application/json");

require_once '../../vendor/autoload.php';
include_once '../../backend/connection.php';
include_once '../../backend/models/user.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    $nome = $data['nome'] ?? null;
    $contacto = $data['contacto'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $confirmPassword = $data['confirmPassword'] ?? null;
    $isAdmin = isset($data['isAdmin']) && $data['isAdmin'] === true ? 1 : 0;

    $isAdmin = ($isAdmin == 1) ? 1 : 0; // Garantir que isAdmin é 0 ou 1

    // Verificar se todos os campos obrigatórios foram preenchidos
    if (!$nome || !$contacto || !$email || !$password || !$confirmPassword) {
        http_response_code(400);
        echo json_encode(["error" => "Todos os campos são obrigatórios."]);
        exit;
    }

    if ($password !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(["error" => "As senhas não coincidem."]);
        exit;
    }

    if (!$connection) throw new Exception("Erro na conexão com o banco de dados.");

    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Este email já está em uso."]);
        exit;
    }

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
?>
