<?php

require '../../vendor/autoload.php';

//var_dump($_POST);

include_once '../../backend/connection.php';
include_once '../../backend/models/user.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {

    // Validar se tipo de pedido é o correto (POST)
    if ('POST' != $_SERVER['REQUEST_METHOD']) {
        throw new Exception('Método não permitido');
    }

    if (count($_POST) != 2) {
        throw new Exception('Número de parâmetros inválidos');
    }
    // Validar se o valor das variáveis é o correto
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        throw new Exception('Parâmetros inválidos');
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (strlen($email) == 0 || strlen($password) == 0) {
        throw new Exception('Dados inválidos!');
    }

    $sqlEmail = "SELECT * FROM utilizadores WHERE email = ? AND password = ?";

    if ($stmt = mysqli_prepare($connection, $sqlEmail)) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $id, $isAdmin, $nome, $dataNascimento, $telefone, $email, $password);

            if (mysqli_stmt_fetch($stmt)) {
                $user = new User($id, $isAdmin, $nome, $dataNascimento, $telefone, $email, $password);
            } else {
                throw new Exception('Utilizador não encontrado!');
            }
        } else {
            throw new Exception('Erro ao executar a query: ' . mysqli_error($connection));
        }
    } else {
        throw new Exception('Erro ao preparar a query: ' . mysqli_error($connection));
    }
    //configurações do JWT
    $key = 'carshub_jwtToken';

    $payload = [
        'iss' => 'http://mydev.carshub.com',
        'aud' => 'http://mydev.carshub.com',
        'iat' => time(),
        'exp' => time() + 3600, // 1 hora
        'data' => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]
    ];

    //criar token
    $jwt = JWT::encode($payload, $key, 'HS256');

    $result = array(
        'status' => 'success',
        'data' => [
            'user' => 'Aqui vem dados do utilizador',
            'jwt' => $jwt
        ],
    );

    echo (json_encode($result));
} catch (Exception $e) {
    $result = [
        'status' => 'FALSE',
        'message' => $e->getMessage()
    ];

    echo json_encode($result);
} finally {
    mysqli_close($connection);
}
