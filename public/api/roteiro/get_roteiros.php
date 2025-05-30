<?php
header("Content-Type: application/json");
require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    verificarToken($connection);

    $sql = "SELECT id, id_tipo_roteiro, nome FROM roteiros";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        throw new Exception("Erro ao buscar roteiros: " . mysqli_error($connection));
    }

    $roteiros = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $roteiros[] = $row;
    }

    echo json_encode($roteiros);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
