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

    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    // Lê o ID da query string
    parse_str(file_get_contents("php://input"), $data);
    $id = $_GET['id'] ?? $data['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido ou não fornecido.");
    }

    // Verifica se existe
    $checkSql = "SELECT id FROM avaliacoes WHERE id = ?";
    $checkStmt = mysqli_prepare($connection, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "i", $id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) === 0) {
        throw new Exception("Avaliação não encontrada.");
    }

    // Elimina a avaliação
    $sql = "DELETE FROM avaliacoes WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "mensagem" => "Avaliação eliminada com sucesso."
        ]);
    } else {
        throw new Exception("Erro ao eliminar avaliação: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
