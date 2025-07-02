<?php
session_start();
require_once '../../backend/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Check if the user is authenticated
if (!isset($_SESSION['user']['id'])) {
    header("Location: /login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['contacto'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        if (empty($name) || empty($email)) {
            throw new Exception("Name and email are required.");
        }

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET nome = ?, email = ?, contacto = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $hashedPassword, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nome = ?, email = ?, contacto = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $userId]);
        }

        // Update session data
        $_SESSION['user']['nome'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['contacto'] = $phone;

        $success = "Profile updated successfully!";
    } catch (Exception $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<main id="main" class="main">
  <div class="container">
    <h1 class="h3 mb-4 text-gray-800">User Profile</h1>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($_SESSION['user']['nome'] ?? '') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="contacto" value="<?= htmlspecialchars($_SESSION['user']['contacto'] ?? '') ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</main>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
