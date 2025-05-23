<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verificarToken($connection) {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["error" => "Token não fornecido."]);
        exit;
    }

    $authHeader = $headers['Authorization'];
    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(["error" => "Formato de token inválido."]);
        exit;
    }

    $token = $matches[1];
    $secretKey = "SUA_CHAVE_SECRETA";

    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Token inválido ou expirado."]);
        exit;
    }
}
?>