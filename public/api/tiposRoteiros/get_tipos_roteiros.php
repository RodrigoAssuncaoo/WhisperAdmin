<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    verificarToken($connection);

    $sql = "SELECT id, nome, duracao, preco FROM tipo_roteiros";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        throw new Exception("Erro ao buscar tipos de roteiro: " . mysqli_error($connection));
    }

    $tipos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tipos[] = $row;
    }

    echo json_encode($tipos);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
