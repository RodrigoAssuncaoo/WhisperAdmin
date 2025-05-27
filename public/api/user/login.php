<?php
header("Content-Type: application/json");


require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(["error" => "Email e senha são obrigatórios."]);
        exit;
    }

    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    $stmt = $connection->prepare("SELECT id, nome, email, password, isAdmin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciais inválidas."]);
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciais inválidas."]);
        exit;
    }

    // Gerar token JWT
    $secretKey = "SUA_CHAVE_SECRETA"; // Substitua por uma constante segura ou carregue de um .env
    $payload = [
        "iss" => "seusite.com",
        "aud" => "seusite.com",
        "iat" => time(),
        "exp" => time() + (60 * 60), // 1 hora de validade
        "data" => [
            "id" => $user['id'],
            "nome" => $user['nome'],
            "email" => $user['email'],
            "isAdmin" => $user['isAdmin']
        ]
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    http_response_code(200);
    echo json_encode([
        "message" => "Login bem-sucedido.",
        "token" => $jwt
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno: " . $e->getMessage()]);
}
