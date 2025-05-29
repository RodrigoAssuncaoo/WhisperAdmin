<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Verifica conexão MySQLi
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    // Obter o ID da query string
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido.");
    }

    $sql = "SELECT id, nome, idPontos FROM roteiros WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    $result = mysqli_stmt_get_result($stmt);
    $roteiro = mysqli_fetch_assoc($result);

    if ($roteiro) {
        echo json_encode($roteiro);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Roteiro não encontrado."]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
