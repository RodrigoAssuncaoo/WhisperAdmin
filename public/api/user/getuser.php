<?php
header("Content-Type: application/json");

require_once '../../vendor/autoload.php';
include_once '../../backend/connection.php';
include_once 'auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro de conexão com o banco de dados.");
    }

    $userData = verificarToken($connection);

    if (!$userData->isAdmin) {
        http_response_code(403);
        echo json_encode(["error" => "Acesso negado. Apenas administradores."]);
        exit;
    }

    $stmt = $connection->prepare("SELECT id, nome, contacto, email, isAdmin FROM users");
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    http_response_code(200);
    echo json_encode($users);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro: " . $e->getMessage()]);
}
?>