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

    verificarToken($connection);

    // Captura os dados do form-data
    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? null;

    // role como tinyint (0,1,2,...)
    // Força converter para inteiro, padrão 0 se não enviado
    $role = isset($_POST['role']) ? (int)$_POST['role'] : 0;

    if (!$id || empty($nome) || empty($contacto) || empty($email)) {
        throw new Exception("Dados obrigatórios em falta.");
    }

    // Verifica se o email já pertence a outro utilizador
    $stmt = mysqli_prepare($connection, "SELECT id FROM users WHERE email = ? AND id != ?");
    mysqli_stmt_bind_param($stmt, "si", $email, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        throw new Exception("E-mail já está em uso por outro utilizador.");
    }
    mysqli_stmt_close($stmt);

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nome = ?, contacto = ?, email = ?, password = ?, role = ?, expires_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        // bind_param: s = string, i = int
        mysqli_stmt_bind_param($stmt, "ssssii", $nome, $contacto, $email, $hashedPassword, $role, $id);
    } else {
        $sql = "UPDATE users SET nome = ?, contacto = ?, email = ?, role = ?, expires_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $nome, $contacto, $email, $role, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(["status" => "success", "mensagem" => "Utilizador atualizado com sucesso."]);
        } else {
            echo json_encode(["status" => "warning", "mensagem" => "Nenhuma alteração feita (dados iguais ou ID inexistente)."]);
        }
    } else {
        throw new Exception("Erro ao atualizar o utilizador.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>