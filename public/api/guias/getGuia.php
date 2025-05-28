<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Verifica conexão MySQLi
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Verifica JWT
    verificarToken($connection);

    // Prepara a query com mysqli
    $sql = "SELECT id, nome, contacto, email, idiomasFalados FROM guias";
    $stmt = mysqli_prepare($connection, $sql);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $id, $nome, $contacto, $email, $idiomasFalados);

        $guias = [];
        while (mysqli_stmt_fetch($stmt)) {
            $guias[] = [
                "id" => $id,
                "nome" => $nome,
                "contacto" => $contacto,
                "email" => $email,
                "idiomasFalados" => $idiomasFalados
            ];
        }

        echo json_encode(["status" => "success", "data" => $guias]);
    } else {
        throw new Exception("Erro ao executar a query.");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
