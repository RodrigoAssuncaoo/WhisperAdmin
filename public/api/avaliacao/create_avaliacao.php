<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Autenticação e obtenção do user_id
    $user = verificarToken($connection);
    $user_id = $user->id ?? null;

    if (!$user_id) {
        throw new Exception("Utilizador não autenticado.");
    }

    // Apenas os campos usados
    $avaliacao_roteiro = isset($_POST['avaliacao_roteiro']) ? (int)$_POST['avaliacao_roteiro'] : null;
    $comentario = $_POST['comentario'] ?? null;

    // Validação
    if (is_null($avaliacao_roteiro) || empty($comentario)) {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    // Query INSERT
    $sql = "INSERT INTO avaliacoes (user_id, avaliacao_roteiro, comentario) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        throw new Exception("Erro ao preparar a query: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "iis", $user_id, $avaliacao_roteiro, $comentario);

    if (mysqli_stmt_execute($stmt)) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "mensagem" => "Avaliação criada com sucesso",
            "id_inserido" => mysqli_insert_id($connection)
        ]);
    } else {
        throw new Exception("Erro ao criar avaliação: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>
