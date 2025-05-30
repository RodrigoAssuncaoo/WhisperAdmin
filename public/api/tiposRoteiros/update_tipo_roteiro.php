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
    $nome = $_POST['nome'] ?? null;
    $duracao = $_POST['duracao'] ?? null;
    $preco = $_POST['preco'] ?? null;

    if (!$id || trim($nome) === "" || !$duracao || !is_numeric($preco)) {
        throw new Exception("Dados inválidos.");
    }

    $sql = "UPDATE tipo_roteiros SET nome = ?, duracao = ?, preco = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ssdi", $nome, $duracao, $preco, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "mensagem" => "Tipo de roteiro atualizado com sucesso."]);
    } else {
        throw new Exception("Erro ao atualizar tipo de roteiro: " . mysqli_stmt_error($stmt));
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
