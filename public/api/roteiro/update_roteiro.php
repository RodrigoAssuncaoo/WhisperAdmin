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

    verificarToken($connection);

    $id = $_POST['id'] ?? null;
    $id_tipo_roteiro = $_POST['id_tipo_roteiro'] ?? null;
    $nome = $_POST['nome'] ?? null;

    if (!$id || !$id_tipo_roteiro || trim($nome) === "") {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    $sql = "UPDATE roteiros SET id_tipo_roteiro = ?, nome = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "isi", $id_tipo_roteiro, $nome, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "mensagem" => "Roteiro atualizado com sucesso"
        ]);
    } else {
        throw new Exception("Erro ao atualizar roteiro: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
