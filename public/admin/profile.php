<?php
session_start();
require_once '../../backend/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user']['id'])) {
    header("Location: /login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        if (empty($nome) || empty($email)) {
            throw new Exception("Nome e email são obrigatórios.");
        }

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET nome = ?, email = ?, contacto = ?, password = ? WHERE id = ?");
            $stmt->execute([$nome, $email, $contacto, $hashedPassword, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nome = ?, email = ?, contacto = ? WHERE id = ?");
            $stmt->execute([$nome, $email, $contacto, $userId]);
        }

        // Atualiza os dados da sessão
        $_SESSION['user']['nome'] = $nome;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['contacto'] = $contacto;

        $success = "Perfil atualizado com sucesso!";
    } catch (Exception $e) {
        $error = "Erro ao atualizar perfil: " . $e->getMessage();
    }
}
?>

<main id="main" class="main">
  <div class="container">
    <h1 class="h3 mb-4 text-gray-800">Perfil do Utilizador</h1>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Nome de Utilizador</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($_SESSION['user']['nome'] ?? '') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contacto</label>
        <input type="text" name="contacto" value="<?= htmlspecialchars($_SESSION['user']['contacto'] ?? '') ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>Nova Senha (deixe em branco para manter)</label>
        <input type="password" name="password" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
  </div>
</main>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
