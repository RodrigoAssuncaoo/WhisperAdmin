<?php
session_start();
include '../../backend/db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit;
}
// Obter dados atuais do utilizador
$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do POST
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    // Verifica se foi fornecida nova password
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, email = ?, contact = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $contact, $hashedPassword, $userId);
    } else {
        // Atualiza sem mudar a password
        $sql = "UPDATE users SET username = ?, email = ?, contact = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $contact, $userId);
    }

    if ($stmt->execute()) {
        // Atualiza os dados na sessão
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['contact'] = $contact;
        $success = "Perfil atualizado com sucesso!";
    } else {
        $error = "Erro ao atualizar perfil.";
    }

    $stmt->close();
}
?>

<main id="main" class="main">
  <div class="container">
    <h1 class="h3 mb-4 text-gray-800">User Profile</h1>

    <?php if (isset($success)) { ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php } elseif (isset($error)) { ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
      <div class="mb-3">
        <label>Nome de Utilizador</label>
        <input type="text" name="username" value="<?php echo $_SESSION['user']['username'] ?? ''; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $_SESSION['user']['email'] ?? ''; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contacto</label>
        <input type="text" name="contact" value="<?php echo $_SESSION['user']['contact'] ?? ''; ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>Senha (deixe em branco para manter a atual)</label>
        <input type="password" name="password" class="form-control">
      </div>
      <button class="btn btn-primary">Guardar</button>
    </form>
  </div>
</main>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
