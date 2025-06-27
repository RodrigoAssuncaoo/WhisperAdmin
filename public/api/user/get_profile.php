<?php
header("Content-Type: application/json; charset=UTF-8");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';
include_once '../../../backend/auth.php';

try {
    if (! $connection) {
        throw new Exception("Erro na conexão com a base de dados.");
    }

    // Verifica JWT e obtém dados do utilizador autenticado (objeto stdClass)
    $authUser = verificarToken($connection);
    $userId   = $authUser->id ?? throw new Exception("Token inválido.");

    // Busca os dados do perfil (sem password)
    $sql = "
        SELECT
            id,
            nome,
            email,
            contacto,
            role,
            token,
            created_at,
            expires_at
        FROM users
        WHERE id = ?
        LIMIT 1
    ";
    $stmt = mysqli_prepare($connection, $sql)
        ?: throw new Exception("Erro ao preparar query de perfil: " . mysqli_error($connection));
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt)
        ?: throw new Exception("Erro ao executar query de perfil: " . mysqli_error($connection));

    mysqli_stmt_bind_result(
        $stmt,
        $id,
        $nome,
        $email,
        $contacto,
        $role,
        $token,
        $createdAt,
        $expiresAt
    );

    if (! mysqli_stmt_fetch($stmt)) {
        throw new Exception("Utilizador não encontrado.");
    }
    mysqli_stmt_close($stmt);

    // Busca os signup tokens do utilizador (opcional)
    $signupTokens = [];
    $sqlTokens = "
        SELECT id, name, value
        FROM signup_tokens
        WHERE user_id = ?
    ";
    if ($stmt = mysqli_prepare($connection, $sqlTokens)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $tokId, $tokName, $tokValue);
        while (mysqli_stmt_fetch($stmt)) {
            $signupTokens[] = [
                'id'    => $tokId,
                'name'  => $tokName,
                'value' => $tokValue,
            ];
        }
        mysqli_stmt_close($stmt);
    }

    // Monta o perfil em camelCase
    $userData = [
        'id'           => $id,
        'name'         => $nome,
        'email'        => $email,
        'contacto'     => $contacto,
        'role'         => $role,
        'roleName'     => (new User(
                             $id,
                             $role,
                             $nome,
                             $contacto,
                             $email,
                             $token,
                             '',         // password ignorada
                             $createdAt
                          ))->getRoleName(),
        'token'        => $token,
        'createdAt'    => $createdAt,
        'expiresAt'    => $expiresAt,
        'signupTokens' => $signupTokens,
    ];

    // Prepara a resposta
    $response = [
        'status'    => 'success',
        'message'   => 'Profile carregado com sucesso',
        'timestamp' => date('c'),
        'data'      => ['user' => $userData],
        'meta'      => [
            'signupTokensCount' => count($signupTokens)
        ],
    ];

    echo json_encode(
        $response,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
