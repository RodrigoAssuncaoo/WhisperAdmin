<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Verifica método (GET ou DELETE — aceitamos os dois)
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    // Obter ID via query string: ?id=5
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido ou não fornecido.");
    }

    // Apagar o ponto
    $stmt = mysqli_prepare($connection, "DELETE FROM pontos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(["status" => "success", "mensagem" => "Ponto eliminado com sucesso"]);
        } else {
            echo json_encode(["status" => "warning", "mensagem" => "ID não encontrado ou já eliminado"]);
        }
    } else {
        throw new Exception("Erro ao eliminar o ponto.");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
