<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

var_dump($_POST);


try {
    // Garante que o método usado é PUT
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    // Verifica ligação à base de dados
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Verifica o token de autenticação
    verificarToken($connection);

    // Captura o corpo da requisição (JSON)
    $rawInput = file_get_contents("php://input");
    file_put_contents("debug.txt", $rawInput); // DEBUG OPCIONAL
    $data = json_decode($rawInput, true);

    // Extrai os dados
    $id = $data['id'] ?? null;
    $nome = $data['nome'] ?? null;
    $longitude = $data['longitude'] ?? null;
    $latitude = $data['latitude'] ?? null;
    $pontoInicial = $data['ponto_inicial'] ?? 0;
    $pontoFinal = $data['ponto_final'] ?? 0;

    // Validações básicas
    if (!$id || !$nome || !is_numeric($longitude) || !is_numeric($latitude)) {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    // Atualiza o ponto na base de dados
    $sql = "UPDATE pontos SET nome = ?, longitude = ?, latitude = ?, ponto_inicial = ?, ponto_final = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "sddiii", $nome, $longitude, $latitude, $pontoInicial, $pontoFinal, $id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode([
                "status" => "success",
                "mensagem" => "Ponto atualizado com sucesso"
            ]);
        } else {
            echo json_encode([
                "status" => "warning",
                "mensagem" => "Nenhuma alteração feita (mesmos dados ou ID inexistente)"
            ]);
        }
    } else {
        throw new Exception("Erro ao atualizar o ponto.");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
