<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    // Obter email e password do POST
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

    // Procurar utilizador na base de dados
    $stmt = $connection->prepare("SELECT id, nome, email, password, role, contacto, created_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciais inválidas."]);
        exit;
    }

    $user = $result->fetch_assoc();

    // Verificar a password
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciais inválidas."]);
        exit;
    }

    // Gerar JWT
    $secretKey = "SUA_CHAVE_SECRETA"; // Trocar por getenv('JWT_SECRET') num .env se possível
    $payload = [
        "iss" => "whisper.app",
        "aud" => "whisper.app",
        "iat" => time(),
        "exp" => time() + (24 * 60 * 60), // 24 horas
        "data" => [
            "id" => $user['id'],
            "nome" => $user['nome'],
            "email" => $user['email'],
            "role" => $user['role'],
            "contacto" => $user['contacto'],
            "created_at" => $user['created_at']
        ]
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    // Criar objeto User (assumindo que o construtor aceita estes parâmetros)
    $userObject = new User(
        $user['id'],
        $user['role'],
        $user['nome'],
        $user['contacto'],
        $user['email'],
        $jwt,
        $user['password'], // cuidado: está a incluir a password encriptada na resposta
        $user['created_at']
    );

    // Resposta para a app
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "token" => $jwt,
        "user" => $userObject->jsonSerialize(), // resposta com os dados + token
        "message" => "Login bem-sucedido."
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno: " . $e->getMessage()]);
}
?>
