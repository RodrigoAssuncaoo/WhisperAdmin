<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Verifica conexão com a base de dados
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Verifica o token de autenticação
    verificarToken($connection);

    // Lê e interpreta o corpo JSON da requisição
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erro ao processar o JSON enviado.");
    }

    // Recolhe e valida os dados
    $id = $data['id'] ?? null;
    $nome = $data['nome'] ?? null;

    if (!$id || !$nome) {
        throw new Exception("Dados inválidos ou incompletos. 'id' e 'nome' são obrigatórios.");
    }

    // Atualiza o nome do roteiro
    $sql = "UPDATE roteiros SET nome = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "si", $nome, $id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode([
                "status" => "success",
                "mensagem" => "Roteiro atualizado com sucesso."
            ]);
        } else {
            echo json_encode([
                "status" => "warning",
                "mensagem" => "Nenhuma alteração feita (ID inexistente ou nome igual)."
            ]);
        }
    } else {
        throw new Exception("Erro ao atualizar o roteiro.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["status" => "error", "mensagem" => $e->getMessage()]);
}
?>
