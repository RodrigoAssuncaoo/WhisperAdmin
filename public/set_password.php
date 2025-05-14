<?php

session_start();
include '../backend/db.php';

if(!isset($_SESSION["set_password_user"])) {
    echo "Acesso não autorizado.";
    exit;
}
$userId = $_SESSION["set_password_user"];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    var_dump($_POST);

    $password = trim($_POST['password']);
    $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
  //var_dump($passwordEncriptada); 

    $sql ="UPDATE users SET password = :password WHERE id = :id";
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

?>

<!-- HTML -->
<!DOCTYPE html>
<html>

<head>
    <title>Definir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2>Defina sua Senha do user <?php echo($userId);?></h2>
    <form method="POST">
    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>
        <button class="btn btn-primary">Salvar</button>
    </form>
</body>

</html>