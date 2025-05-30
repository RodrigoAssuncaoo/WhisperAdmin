<?php
require_once '../backend/connection.php'; // Usa o teu ficheiro de ligação

if (!isset($_GET['token'])) {
    echo "Token não fornecido.";
    exit;
}

$token = $_GET['token'];

// Verifica se existe um utilizador com esse token
$stmt = $connection->prepare("SELECT id FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Token inválido ou já usado.";
    exit;
}

// Atualiza o utilizador para verificar o email
$stmt = $connection->prepare("UPDATE users SET email_verificado = 1, token = NULL WHERE token = ?");
$stmt->bind_param("s", $token);
if ($stmt->execute()) {
    echo "✅ Email verificado com sucesso. Pode agora fazer login.";
} else {
    echo "❌ Ocorreu um erro ao verificar o email.";
}
?>
