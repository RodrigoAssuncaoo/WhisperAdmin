<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verificarToken($connection) {
    // Obter os headers da requisição
    $headers = getallheaders();

    // Permitir também minúsculas (servidores como nginx podem alterar os headers)
    $authorizationHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    if (!$authorizationHeader) {
        http_response_code(401);
        echo json_encode(["error" => "Token não fornecido."]);
        exit;
    }

    // Extrair o token com regex
    if (!preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
        http_response_code(401);
        echo json_encode(["error" => "Formato de token inválido."]);
        exit;
    }

    $token = $matches[1];
    $secretKey = "SUA_CHAVE_SECRETA"; // Usa preferencialmente variável de ambiente

    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

        // Retorna os dados decodificados (id, email, etc.)
        return $decoded->data;

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Token inválido ou expirado."]);
        exit;
    }
}
?>
