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
    $nome = $data['nome'] ?? null;
    $longitude = $data['longitude'] ?? null;
    $latitude = $data['latitude'] ?? null;
    $pontoInicial = $data['ponto_inicial'] ?? 0;
    $pontoFinal = $data['ponto_final'] ?? 0;

    if (!$id || !$nome || !is_numeric($longitude) || !is_numeric($latitude)) {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    $sql = "UPDATE pontos SET nome = ?, longitude = ?, latitude = ?, ponto_inicial = ?, ponto_final = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "sddiii", $nome, $longitude, $latitude, $pontoInicial, $pontoFinal, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "mensagem" => "Ponto atualizado com sucesso"]);
    } else {
        throw new Exception("Erro ao atualizar o ponto.");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}   
?>
