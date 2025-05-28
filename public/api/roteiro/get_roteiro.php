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

<<<<<<< HEAD:public/api/guias/getGuia.php
    // Verifica JWT
    verificarToken($connection);

    // Prepara a query com mysqli
    $sql = "SELECT id, nome, contacto, email, idiomasFalados FROM guias";
=======
    verificarToken($connection);

    // Obter o ID da query string
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        throw new Exception("ID inválido.");
    }

    $sql = "SELECT id, nome, idPontos FROM roteiros WHERE id = ?";
>>>>>>> f404ee1cf304152be7732dc585bb81a69787074e:public/api/roteiro/get_roteiro.php
    $stmt = mysqli_prepare($connection, $sql);

<<<<<<< HEAD:public/api/guias/getGuia.php
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
=======
    $result = mysqli_stmt_get_result($stmt);
    $roteiro = mysqli_fetch_assoc($result);

    if ($roteiro) {
        echo json_encode($roteiro);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Avaliacao não encontrado."]);
>>>>>>> f404ee1cf304152be7732dc585bb81a69787074e:public/api/roteiro/get_roteiro.php
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
