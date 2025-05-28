<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Verifica o método (aceita DELETE ou GET para testes)
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    // Verifica ligação à base de dados
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Verifica token de autenticação
    verificarToken($connection);

    // Obtém o ID via query string
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido ou não fornecido.");
    }

    // Prepara e executa a query de eliminação
    $stmt = mysqli_prepare($connection, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(["status" => "success", "mensagem" => "Utilizador eliminado com sucesso."]);
        } else {
            echo json_encode(["status" => "warning", "mensagem" => "Utilizador não encontrado ou já eliminado."]);
        }
    } else {
        throw new Exception("Erro ao eliminar o utilizador.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
