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

    $nome = $_POST['nome'] ?? null;
    $duracao = $_POST['duracao'] ?? null;
    $preco = $_POST['preco'] ?? null;

    if (trim($nome) === "" || !$duracao || !is_numeric($preco)) {
        throw new Exception("Dados inválidos.");
    }

    $sql = "INSERT INTO tipo_roteiros (nome, duracao, preco) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ssd", $nome, $duracao, $preco);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "id_inserido" => mysqli_insert_id($connection)
        ]);
    } else {
        throw new Exception("Erro ao criar tipo de roteiro: " . mysqli_stmt_error($stmt));
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
