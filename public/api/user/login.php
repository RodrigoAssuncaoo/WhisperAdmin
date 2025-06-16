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

    // Preparando e executando a consulta ao banco de dados
    $stmt = $connection->prepare("SELECT id, nome, email, password, role, contacto, created_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciais inválidas."]);
        exit;
    }

    // Recuperando o resultado da consulta como array associativo
    $user = $result->fetch_assoc();

    // Verificando a senha
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciais inválidas."]);
        exit;
    }

    // Gerar o token JWT
    $secretKey = "SUA_CHAVE_SECRETA"; // Substitua por uma constante segura ou carregue de um .env
    $payload = [
        "iss" => "seusite.com",
        "aud" => "seusite.com",
        "iat" => time(),
        "exp" => time() + (24 * 60 * 60), // 24 horas de validade
        "data" => [
            "id" => $user['id'],
            "nome" => $user['nome'],
            "email" => $user['email'],
            "role" => $user['role'],
            "contacto" => $user['contacto'],
            "created_at" => $user['created_at'],        
            ]
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    // Criando o objeto User e passando os dados corretos
    $userObject = new User(
        $user['id'],
        $user['role'],
        $user['nome'],
        $user['contacto'],
        $user['email'],
        $jwt, // Passando o token JWT para o objeto User
        $user['password'],
        $user['created_at'],
    );

    // Respondendo com sucesso
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "token" => $jwt,
        "user" => $userObject->jsonSerialize(), // Chamando o método jsonSerialize para enviar os dados do usuário
        "message" => "Login bem-sucedido."
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno: " . $e->getMessage()]);
}
?>
