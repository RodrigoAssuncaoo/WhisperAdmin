<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    // Recolher os dados do form-data
    $id = $_POST['id'] ?? null;
    $avaliacao_viagem = isset($_POST['avaliacao_viagem']) ? (int)$_POST['avaliacao_viagem'] : null;
    $comentario = $_POST['comentario'] ?? null;

    // Validação
    if (!$id || !is_numeric($id) || is_null($avaliacao_viagem) || trim($comentario) === "") {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    // Atualizar avaliação
    $sql = "UPDATE avaliacoes 
            SET avaliacao_viagem = ?, comentario = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        throw new Exception("Erro ao preparar a query: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "isi", $avaliacao_viagem, $comentario, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "mensagem" => "Avaliação atualizada com sucesso"
        ]);
    } else {
        throw new Exception("Erro ao atualizar avaliação: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
