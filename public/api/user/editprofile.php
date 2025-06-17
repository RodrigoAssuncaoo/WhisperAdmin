<?php
require '../../backend/db.php';
require '../../backend/models/user.php';
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$secretKey = 'jargliveforever';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? '';
    $token = '';

    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } elseif (!empty($_POST['token'])) {
        $token = $_POST['token'];
    }

    if (!$token) {
        throw new Exception('Token não fornecido');
    }

    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    $userId = $decoded->data->user->id;

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $password = isset($_POST['password']) && strlen($_POST['password']) > 0 ? trim($_POST['password']) : '';

    if ($name === '' || $email === '') {
        throw new Exception('Nome e email são obrigatórios');
    }

    if (strlen($password) > 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE users SET name = ?, email = ?, contacto = ?, password = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $contacto, $hashedPassword, $userId]);
    } else {
        $sql = 'UPDATE users SET name = ?, email = ?, contacto = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $contacto, $userId]);
    }

    if ($stmt->rowCount() === 0) {
        throw new Exception('Nenhuma alteração efetuada');
    }

    // Obter dados atualizados
    $sqlSelect = 'SELECT id, name, email, contacto, role, password FROM users WHERE id = ?';
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->execute([$userId]);
    $updatedUser = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    $user = new User(
        $updatedUser['id'],
        $updatedUser['name'],
        $updatedUser['email'],
        $updatedUser['contacto'] ?? '',
        $updatedUser['role'] ?? 3,
        $token,
        $updatedUser['password'] ?? '',
        date('Y-m-d H:i:s')
    );

    echo json_encode([
        'success' => true,
        'message' => 'Perfil atualizado com sucesso',
        'user' => $user->jsonSerialize(),
        'token' => $token,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
