<?php
require_once '../../../backend/db.php';
session_start(); // Inicia a sessão

try {
    // Verifica se o usuário está logado e se a role é admin
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Acesso não autorizado.");
    }

    // Obtém o ID do usuário logado
    $user_id = $_SESSION['user_id'];

    // Verifica a role do usuário
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['role'] == 1) {
        // O usuário é admin
        echo "Bem-vindo, admin!";
    } else {
        // O usuário não é admin
        throw new Exception("Acesso restrito. Somente administradores podem acessar esta página.");
    }

} catch (Exception $e) {
    // Define a mensagem de erro na sessão
    $_SESSION['error'] = $e->getMessage();
    header("Location: index.php?erro=" . urlencode($e->getMessage()));
    exit;
}
?>
