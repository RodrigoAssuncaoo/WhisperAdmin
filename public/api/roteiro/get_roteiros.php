<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/roteiros.php';
include_once '../../../backend/auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection); // Verificação JWT

    $sql = "SELECT id, nome, idPontos FROM roteiros";
    $stmt = mysqli_prepare($connection, $sql);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $id, $nome, $idPontos);

        $roteiros = [];
        while (mysqli_stmt_fetch($stmt)) {
            $roteiros[] = new Roteiro($id, $nome, $idPontos);
        }

        echo json_encode(["status" => "success", "data" => $pontos]);
    } else {
        throw new Exception("Erro ao executar a query.");
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
