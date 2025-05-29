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

    // Lê dados via POST form-data
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $nome = $_POST['nome'] ?? null;
    $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;
    $latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $pontoInicial = isset($_POST['ponto_inicial']) ? (int)$_POST['ponto_inicial'] : 0;
    $pontoFinal = isset($_POST['ponto_final']) ? (int)$_POST['ponto_final'] : 0;

    if (empty($id) || empty($nome) || !is_numeric($longitude) || !is_numeric($latitude)) {
    $erros = [];
    if (empty($id)) $erros[] = "ID vazio";
    if (empty($nome)) $erros[] = "Nome vazio";
    if (!is_numeric($longitude)) $erros[] = "Longitude inválida";
    if (!is_numeric($latitude)) $erros[] = "Latitude inválida";
    throw new Exception("Dados inválidos: " . implode(", ", $erros));
}

    $sql = "UPDATE pontos SET nome = ?, longitude = ?, latitude = ?, ponto_inicial = ?, ponto_final = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        throw new Exception("Erro ao preparar a query: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "sddiii", $nome, $longitude, $latitude, $pontoInicial, $pontoFinal, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "mensagem" => "Ponto atualizado com sucesso"]);
    } else {
        throw new Exception("Erro ao atualizar o ponto: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>