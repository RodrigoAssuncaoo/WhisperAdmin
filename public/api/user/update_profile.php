<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';  // Conexão com o banco de dados
include_once '../../../backend/models/user.php';  // Model de usuário

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = 'SUA_CHAVE_SECRETA'; // Substitua pela sua chave secreta

try {
    // Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Método não permitido');
    }

    // Captura o token de autenticação do cabeçalho ou do corpo da requisição
    $headers = function_exists('apache_request_headers') ? apache_request_headers() : getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    $token = '';

    // Se o token estiver no cabeçalho Authorization
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } elseif (!empty($_POST['token'])) {
        // Se o token estiver no corpo da requisição
        $token = $_POST['token'];
    }

    // Verifica se o token foi fornecido
    if (!$token) {
        throw new Exception('Token não fornecido');
    }

    // Decodifica o token
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

    // Ajusta aqui dependendo da estrutura do token
    $userId = $decoded->data->id ?? null;

    // Verifica se o ID do usuário está presente no token
    if (!$userId) {
        throw new Exception('Token inválido: ID do utilizador não encontrado.');
    }

    // Obtém os dados enviados para atualizar o perfil
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $password = isset($_POST['password']) && strlen($_POST['password']) > 0 ? trim($_POST['password']) : '';

    // Verifica se o nome foi fornecido
    if ($nome === '') {
        throw new Exception('Nome é obrigatório');
    }

    // Atualiza o perfil no banco de dados
    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE users SET nome = ?, email = ?, contacto = ?, password = ? WHERE id = ?';
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssi', $nome, $email, $contacto, $hashedPassword, $userId); // Usando bind_param para segurança
        $stmt->execute();
    } else {
        $sql = 'UPDATE users SET nome = ?, email = ?, contacto = ? WHERE id = ?';
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('sssi', $nome, $email, $contacto, $userId); // Usando bind_param para segurança
        $stmt->execute();
    }

    // Verifica se nenhuma linha foi atualizada
    if ($stmt->affected_rows === 0) {
        throw new Exception('Nenhuma alteração efetuada');
    }

    // Busca os dados atualizados do usuário
    $sqlSelect = 'SELECT id, nome, email, contacto, role, created_at FROM users WHERE id = ?';
    $stmtSelect = $connection->prepare($sqlSelect);
    $stmtSelect->bind_param('i', $userId); // Usando bind_param para segurança
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();
    $updatedUser = $result->fetch_assoc();

    // Verifica se os dados do usuário foram recuperados
    if (!$updatedUser) {
        throw new Exception('Erro ao buscar utilizador atualizado');
    }

    // Cria um objeto User (ajuste conforme a sua classe personalizada)
    $user = new User(
        $updatedUser['id'],
        $updatedUser['role'],
        $updatedUser['nome'],
        $updatedUser['contacto'] ?? '',
        $updatedUser['email'],
        $token, // Passando o token JWT para o objeto User
        '', // Não devolver a password
        $updatedUser['created_at']
    );

    // Retorna a resposta de sucesso
    echo json_encode([
        'success' => true,
        'message' => 'Perfil atualizado com sucesso',
        'user' => $user->jsonSerialize(),
        'token' => $token,
    ]);

} catch (Exception $e) {
    // Se ocorrer um erro, retorna a mensagem de erro
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => $e->getTrace()  // Incluir o trace de erro para depuração (opcional)
    ]);
}
?>
