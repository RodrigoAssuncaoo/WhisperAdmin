<?php
//echo('teste');

include '../backend/db.php';
if(!isset($_GET['token'])) {
    echo "Token não fornecido.";
    exit;
}

$token = $_GET['token'];
var_dump($token);
//$sql = "SELECT * FROM signup_tokens WHERE token = :token AND expires_at > NOW()";
$sql = "SELECT * FROM signup_tokens WHERE token = :token";
$stmt = $pdo->prepare($sql);
$stmt->execute(['token' => $token]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump($result);

if(!$result || strtotime($result['expires_at']) < time()) {
    echo "Token inválido ou expirado.";
    exit;
}

session_start();
$_SESSION["set_password_user"] = $result['user_id']; 

header('Location: /set_password.php');
exit;
?>
