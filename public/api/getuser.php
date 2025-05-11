<?php

require '../../vendor/autoload.php';

//var_dump($_POST);

include_once '../../backend/connection.php';
include_once '../../backend/models/user.php';

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try{
    $headers = getallheaders();
    
    if(!isset($headers['Authorization'])){
        throw new Exception('Token não encontrado!');
    } 

    $tokenWithBearer = $headers['Authorization'];
//preg_match analisa strings         //guarda o Bearer  
    if(preg_match('/Bearer\s(\S+)/', $tokenWithBearer, $matches)){
        $jwt = $matches[1];
    
    } else {
        throw new Exception('Token inválido!');
    }

    //fazer decode do jwt
    //$jwt = JWT::encode($payload, $key, 'HS256');
    $key = 'friendlistbackend_jwtToken';
    $tokenDecoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $userId = $_GET['userId'];

    $sql = "SELECT * FROM users WHERE id = ?";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $id, $name, $address, $email, $password, $photo);
            
            if (mysqli_stmt_fetch($stmt)) {
                $users = new User($id, $email, $password); // Ajusta conforme o teu construtor da classe User
            }

        } else {
            throw new Exception('Erro ao executar a query: ' . mysqli_error($connection));
        }
    } else {
        throw new Exception('Erro ao preparar a query: ' . mysqli_error($connection));
    }
    $result = array(
        'status' => 'success',
        'data' => [
            'users' => $users,
        ],
    );

    echo(json_encode($result));

}catch (expiredException $e) {
    $result = [
        'status' => 'FALSE',
        'message' => 'Token expirado!'
    ];

    echo json_encode($result);

}catch (InvalidArgumentException $e) {
    $result = [
        'status' => 'FALSE',
        'message' => 'Token inválido!'
    ];

    echo json_encode($result);
}
?>