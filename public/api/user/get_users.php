<?php
header("Content-Type: application/json; charset=UTF-8");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';
include_once '../../../backend/auth.php';

try {
    if (!$connection) {
        throw new Exception("Erro na conexão com a base de dados.");
    }

    // Verifica autenticação JWT (lê o token e valida; poderá usar $userData para controlo de acesso)
    $userData = verificarToken($connection);

    // Query: buscamos todos os campos relevantes, menos a password
    $sql = "
        SELECT 
            id,
            role,
            nome,
            contacto,
            email,
            token,
            created_at,
            expires_at
        FROM users
    ";

    if (! $stmt = mysqli_prepare($connection, $sql)) {
        throw new Exception("Erro ao preparar a query: " . mysqli_error($connection));
    }

    if (! mysqli_stmt_execute($stmt)) {
        throw new Exception("Erro ao executar a query: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_result(
        $stmt,
        $id,
        $role,
        $nome,
        $contacto,
        $email,
        $token,
        $created_at,
        $expires_at
    );

    $users = [];
    while (mysqli_stmt_fetch($stmt)) {
        // Constrói um array simples para cada utilizador
        $users[] = [
            'id'         => $id,
            'role'       => $role,
            'roleName'   => (new User($id, $role, $nome, $contacto, $email, $token, '', $created_at))
                                ->getRoleName(),
            'nome'       => $nome,
            'contacto'   => $contacto,
            'email'      => $email,
            'token'      => $token,          // pode ser null
            'createdAt'  => $created_at,
            'expiresAt'  => $expires_at,
        ];
    }

    // Monta a resposta com metadados + dados
    $response = [
        'status'   => 'success',
        'count'    => count($users),
        'timestamp'=> date('c'),
        'data'     => $users,
    ];

    echo json_encode(
        $response,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message'=> $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
