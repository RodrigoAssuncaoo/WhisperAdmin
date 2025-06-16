<?php
require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/models/user.php';
include_once '../../../backend/auth.php';

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json");

try {
    // Obter o cabeçalho de autorização (compatível com qualquer servidor)
    $headers = [];

    if (function_exists('getallheaders')) {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
    } else {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers['authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers['authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
    }

    if (!isset($headers['authorization'])) {
        throw new Exception('Token não encontrado!');
    }

    $tokenWithBearer = $headers['authorization'];

    // Extrair o token do cabeçalho
    if (preg_match('/Bearer\s(\S+)/', $tokenWithBearer, $matches)) {
        $jwt = $matches[1];
    } else {
        throw new Exception('Token inválido!');
    }

    // Decodificar o token
    $key = 'SUA_CHAVE_SECRETA'; // deve ser a mesma usada na geração
    $tokenDecoded = JWT::decode($jwt, new Key($key, 'HS256'));

    // Obter ID do utilizador a partir do token
    $userId = $tokenDecoded->data->id;

    // Buscar dados do utilizador
    $sql = "SELECT id, role, nome, contacto, email, token, password, created_at FROM users WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Aqui deve haver o número correto de variáveis para bind_result
        $stmt->bind_result($id, $role, $nome, $contacto, $email, $token, $password, $created_at);

        if ($stmt->fetch()) {
            // Criar o objeto User com os dados obtidos
            $user = new User($id, $role, $nome, $contacto, $email, $token, $password, $created_at);

            echo json_encode([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            throw new Exception('Utilizador não encontrado!');
        }
    } else {
        throw new Exception('Erro ao executar a query: ' . $stmt->error);
    }

    $stmt->close();
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
