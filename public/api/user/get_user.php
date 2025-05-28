<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';
include_once '../../../backend/auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Verificar autenticação JWT
    $userData = verificarToken($connection);

    // Verifica se o parâmetro 'id' foi enviado
    if (!isset($_GET['id'])) {
        throw new Exception("Parâmetro 'id' não fornecido.");
    }

    $id = intval($_GET['id']); // Proteção básica contra injeção

    $sql = "SELECT id, isAdmin, nome, contacto, email, token, password, created_at, expires_at FROM users WHERE id = ?";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result(
                $stmt,
                $id,
                $isAdmin,
                $nome,
                $contacto,
                $email,
                $token,
                $password,
                $created_at,
                $expires_at
            );

            if (mysqli_stmt_fetch($stmt)) {
                $user = new User($id, $isAdmin, $nome, $contacto, $email, $token, $password, $created_at, $expires_at);

                echo json_encode([
                    "status" => "success",
                    "data" => $user
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Usuário não encontrado."]);
            }
        } else {
            throw new Exception("Erro ao executar a query: " . mysqli_error($connection));
        }
    } else {
        throw new Exception("Erro ao preparar a query: " . mysqli_error($connection));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
