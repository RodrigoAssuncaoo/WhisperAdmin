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

    $sql = "SELECT id,user_id, avaliacao_viagem, comentario 
            FROM avaliacoes";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        throw new Exception("Erro ao executar a query: " . mysqli_error($connection));
    }

    $avaliacoes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $avaliacoes[] = $row;
    }

    echo json_encode($avaliacoes);

    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
