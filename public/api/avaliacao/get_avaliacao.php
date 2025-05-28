<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    // Obter o ID da query string
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido.");
    }

    $sql = "SELECT id, idRoteiroCompras, idGrupoVisitas, avaliacaoGuia, avaliacaoViagem, comentario FROM avaliacoes WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "iiiiis", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $avaliacao = mysqli_fetch_assoc($result);

    if ($avaliacao) {
        echo json_encode($avaliacao);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Avaliacao não encontrado."]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
