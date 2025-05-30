<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    verificarToken($connection);

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido.");
    }

    $sql = "DELETE FROM tipo_roteiros WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "mensagem" => "Tipo de roteiro eliminado com sucesso."]);
    } else {
        throw new Exception("Erro ao eliminar tipo de roteiro: " . mysqli_stmt_error($stmt));
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
