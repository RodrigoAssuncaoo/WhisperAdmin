<?php
require_once '../backend/db.php';
require_once '../vendor/autoload.php'; // para usar a biblioteca JWT

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Chave secreta usada para assinar o token JWT (deve ser igual à usada na geração)
$jwt_secret = 'chave_secreta_super_segura';

// 1. Verifica se o token foi enviado no cabeçalho Authorization
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(["erro" => "Token JWT em falta"]);
    exit;
}

// 2. Extrai o token (espera-se: Bearer <token>)
$authHeader = $headers['Authorization'];
list($type, $jwt) = explode(" ", $authHeader);

// 3. Valida o token
try {
    $decoded = JWT::decode($jwt, new Key($jwt_secret, 'HS256'));
} catch (Exception $e) {
    echo json_encode(["erro" => "Token inválido: " . $e->getMessage()]);
    exit;
}

// 4. Verifica se o ID foi passado por GET
if (!isset($_GET['id'])) {
    echo json_encode(["erro" => "Parâmetro 'id' em falta"]);
    exit;
}

$id = intval($_GET['id']);

// 5. Consulta ao utilizador com o ID fornecido
$stmt = $conn->prepare("SELECT id, nome, email, contacto, isAdmin, created_at, updated_at FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// 6. Devolve o resultado
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(["erro" => "Utilizador não encontrado"]);
}

$stmt->close();
$conn->close();
?>
