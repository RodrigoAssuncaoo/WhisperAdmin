<?php

require '../../vendor/autoload.php';

include_once '../../backend/connection.php';
include_once '../../backend/models/guias.php';

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

    $sql = "SELECT * FROM guias";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $id, $nome, $contacto, $email, $idiomasFalados);

            $guias = [];
            while (mysqli_stmt_fetch($stmt)) {
                $guias[] = new Guia($id, $nome, $contacto, $email, $idiomasFalados);
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
            'guias' => $guias,
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
