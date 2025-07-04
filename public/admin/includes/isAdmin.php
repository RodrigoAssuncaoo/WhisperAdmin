<?php
// Ativa a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../backend/db.php'; // Ajusta o caminho se necessário

try {
    // Verifica se o utilizador está autenticado
    if (!isset($_SESSION['user_id'])) {
        // Redireciona para login se não estiver autenticado
        header("Location: /login.php");
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Verifica se o utilizador é admin (role = 1)
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['role'] != 1) {
        // Se não for admin, redireciona para o site normal
        header("Location: /index.php");
        exit;
    }

} catch (Exception $e) {
    // Em caso de erro, redireciona para o site normal
    header("Location: /index.php");
    exit;
}
