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

    $id_tipo_roteiro = $_POST['id_tipo_roteiro'] ?? null;
    $nome = $_POST['nome'] ?? null;

    if (!$id_tipo_roteiro || trim($nome) === "") {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    $sql = "INSERT INTO roteiros (id_tipo_roteiro, nome) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id_tipo_roteiro, $nome);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "mensagem" => "Roteiro criado com sucesso",
            "id_inserido" => mysqli_insert_id($connection)
        ]);
    } else {
        throw new Exception("Erro ao criar roteiro: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
