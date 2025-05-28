<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/avaliacoes.php';
include_once '../../../backend/auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection); // Verificação JWT

    $sql = "SELECT id, idRoteiroCompras, idGrupoVisitas, avaliacaoViagem, comentario FROM avaliacoes";
    $stmt = mysqli_prepare($connection, $sql);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $id, $idRoteiroCompras, $idGrupoVisitas, $avaliacaoViagem, $comentario);

        $avaliacoes = [];
        while (mysqli_stmt_fetch($stmt)) {
            $avaliacoes[] = new Avaliacao($id, $idRoteiroCompras, $idGrupoVisitas, $avaliacaoViagem, $comentario);
        }

        echo json_encode(["status" => "success", "data" => $avaliacoes]);
    } else {
        throw new Exception("Erro ao executar a query.");
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
