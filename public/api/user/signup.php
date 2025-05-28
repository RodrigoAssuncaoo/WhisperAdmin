<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    // Lê dados do formulário (form-data)
    $nome = $_POST['nome'] ?? null;
    $contacto = $_POST['contacto'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    // Verifica campos obrigatórios
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

    // Verifica se já existe o email
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
    $role = 3; // Valor fixo para novos usuários

    $stmt = $connection->prepare("INSERT INTO users (nome, contacto, email, password, role, created_at, expires_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssssi", $nome, $contacto, $email, $hashedPassword, $role);

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
