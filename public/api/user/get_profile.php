<?php

require '../../vendor/autoload.php';

include_once '../../backend/connection.php';
include_once '../../backend/models/user.php';

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    $headers = array_change_key_case(getallheaders(), CASE_LOWER);

    if (!isset($headers['authorization'])) {
        throw new Exception('Token não encontrado!');
    }

    $tokenWithBearer = $headers['authorization'];

    if (preg_match('/Bearer\s(\S+)/', $tokenWithBearer, $matches)) {
        $jwt = $matches[1];
    } else {
        throw new Exception('Token inválido!');
    }

    $key = 'whisper_jwtToken';
    $tokenDecoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $userId = $tokenDecoded->data->id;

    $sql = "SELECT * FROM users WHERE id = ?";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $id, $name, $address, $email, $password, $photo);

            if (mysqli_stmt_fetch($stmt)) {
                $user = new User($id, $email, $password); // Ajusta se tiveres mais parâmetros
            } else {
                throw new Exception('Utilizador não encontrado!');
            }

            mysqli_stmt_close($stmt);
        } else {
            throw new Exception('Erro ao executar a query: ' . mysqli_error($connection));
        }
    } else {
        throw new Exception('Erro ao preparar a query: ' . mysqli_error($connection));
    }

    $result = array(
        'status' => 'success',
        'data' => [
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ],
    );

    echo json_encode($result);

} catch (ExpiredException $e) {
    echo json_encode([
        'status' => 'FALSE',
        'message' => 'Token expirado!'
    ]);

} catch (InvalidArgumentException $e) {
    echo json_encode([
        'status' => 'FALSE',
        'message' => 'Token inválido!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'FALSE',
        'message' => $e->getMessage()
    ]);
}

?>
