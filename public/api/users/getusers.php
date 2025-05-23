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
    $userData = verificarToken($connection); // opcional: pode usar esse retorno pra limitar acesso se quiser

    $sql = "SELECT id, isAdmin, nome, contacto, email, token, password, created_at, expires_at FROM users";

    if ($stmt = mysqli_prepare($connection, $sql)) {
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

            $users = [];
            while (mysqli_stmt_fetch($stmt)) {
                $users[] = new User($id, $isAdmin, $nome, $contacto, $email, $token, $password, $created_at, $expires_at);
            }

            echo json_encode([
                "status" => "success",
                "data" => $users
            ]);
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