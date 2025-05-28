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
        throw new Exception("Erro na conexão com a base de dados.");
    }

    verificarToken($connection);

    // Lê o corpo da requisição
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erro ao interpretar o JSON enviado.");
    }

    $nome = $data['nome'] ?? null;

    if (!$nome) {
        throw new Exception("O nome do roteiro é obrigatório.");
    }

    $sql = "INSERT INTO roteiros (nome) VALUES (?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nome);

    if (mysqli_stmt_execute($stmt)) {
        $idNovo = mysqli_insert_id($connection);

        echo json_encode([
            "status" => "success",
            "mensagem" => "Roteiro criado com sucesso.",
            "id" => $idNovo,
            "nome" => $nome
        ]);
    } else {
        throw new Exception("Erro ao criar o roteiro.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["status" => "error", "mensagem" => $e->getMessage()]);
}
?>
