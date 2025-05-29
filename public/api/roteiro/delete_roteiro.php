<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Permitir apenas DELETE (ou GET para testes, opcional)
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    if (!$connection) {
        throw new Exception("Erro na conexão com a base de dados.");
    }

    verificarToken($connection);

    // Obter o ID do roteiro
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        throw new Exception("ID do roteiro inválido ou não fornecido.");
    }

    // Eliminar o roteiro
    $stmt = mysqli_prepare($connection, "DELETE FROM roteiros WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(["status" => "success", "mensagem" => "Roteiro eliminado com sucesso."]);
        } else {
            echo json_encode(["status" => "warning", "mensagem" => "ID não encontrado ou já eliminado."]);
        }
    } else {
        throw new Exception("Erro ao eliminar o roteiro.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "mensagem" => "Erro: " . $e->getMessage()
    ]);
}
?>
