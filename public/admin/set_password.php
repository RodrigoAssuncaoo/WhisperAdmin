<?php

session_start();
include '../backend/db.php';

if (!isset($_SESSION["set_password_user"])) {
    echo "Acesso não autorizado.";
    exit;
}
$userId = $_SESSION["set_password_user"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST);

    $password = trim($_POST['password']);
    $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
    //var_dump($passwordEncriptada); 

    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $passwordEncriptada, 'id' => $userId]);
    $sqldelete = "DELETE FROM signup_tokens WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sqldelete);
    $stmt->execute(['user_id' => $userId]);
    // Apagar a sessão
    session_destroy();
    // Redirecionar para a página de login
    header('Location: /login.php');
    exit;
}
