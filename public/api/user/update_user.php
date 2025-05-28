<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Garante que o método é PUT
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

    // Captura e valida os dados do corpo da requisição
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erro ao interpretar JSON.");
    }

    $id = $data['id'] ?? null;
    $nome = trim($data['nome'] ?? '');
    $contacto = trim($data['contacto'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? null;
    $isAdmin = isset($data['isAdmin']) ? (bool)$data['isAdmin'] : false;

    if (!$id || empty($nome) || empty($contacto) || empty($email)) {
        throw new Exception("Dados obrigatórios em falta.");
    }

    // Verifica se o e-mail pertence a outro utilizador
    $stmt = mysqli_prepare($connection, "SELECT id FROM users WHERE email = ? AND id != ?");
    mysqli_stmt_bind_param($stmt, "si", $email, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        throw new Exception("E-mail já está em uso por outro utilizador.");
    }
    mysqli_stmt_close($stmt);

    // Se password for enviada, atualiza também
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nome = ?, contacto = ?, email = ?, password = ?, isAdmin = ?, updated_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ssssii", $nome, $contacto, $email, $hashedPassword, $isAdmin, $id);
    } else {
        $sql = "UPDATE users SET nome = ?, contacto = ?, email = ?, isAdmin = ?, updated_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $nome, $contacto, $email, $isAdmin, $id);
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
