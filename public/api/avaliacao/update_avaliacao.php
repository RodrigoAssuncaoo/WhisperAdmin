<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? null;
    $idRoteiroCompras = $data['idRoteiroCompras'] ?? null;
    $idGrupoVisitas = $data['idGrupoVisitas'] ?? null;
    $avaliacaoGuia = $data['avaliacaoGuia'] ?? null;
    $avaliacaoViagem = $data['avaliacaoViagem'] ?? 0;
    $comentario = $data['comentario'] ?? null;
    $userId = $data['userId'] ?? null;

    if (!$id || !$idRoteiroCompras || !$idGrupoVisitas || !$avaliacaoGuia || !$avaliacaoViagem || !$comentario || !$userId) {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    $sql = "UPDATE avaliacoes SET idRoteiroCompras = ?, idGrupoVisitas = ?, avaliacaoGuia = ?, avaliacaoViagem = ?, comentario = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "iiiisii",!$idRoteiroCompras, !$idGrupoVisitas, !$avaliacaoGuia, !$avaliacaoViagem, !$comentario, $id, $userId);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "mensagem" => "avaliacao atualizada com sucesso"]);
    } else {
        throw new Exception("Erro ao atualizar a avaliacao.");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}   
?>
