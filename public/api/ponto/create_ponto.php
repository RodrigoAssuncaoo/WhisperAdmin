<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Só aceita POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    verificarToken($connection);

    // Lê os dados enviados via form-data no POST
    $nome = $_POST['nome'] ?? null;
    $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;
    $latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $pontoInicial = isset($_POST['ponto_inicial']) ? (int)$_POST['ponto_inicial'] : 0;
    $pontoFinal = isset($_POST['ponto_final']) ? (int)$_POST['ponto_final'] : 0;

    // Validação dos dados obrigatórios
    if (empty($nome) || !is_numeric($longitude) || !is_numeric($latitude)) {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    // Query para inserir novo ponto
    $sql = "INSERT INTO pontos (nome, longitude, latitude, ponto_inicial, ponto_final) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        throw new Exception("Erro ao preparar a query: " . mysqli_error($connection));
    }

    // Bind dos parâmetros - 5 parâmetros, tipo s d d i i
    mysqli_stmt_bind_param($stmt, "sddii", $nome, $longitude, $latitude, $pontoInicial, $pontoFinal);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "mensagem" => "Ponto criado com sucesso",
            "id_inserido" => mysqli_insert_id($connection)
        ]);
    } else {
        throw new Exception("Erro ao criar ponto: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}