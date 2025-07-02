<?php
session_start();
require_once '../../backend/db.php';
include 'includes/styles.php';

// Login logic WITHOUT reCAPTCHA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user'] = $user; // store name, email, role, etc.
      header('Location: index.php');
      exit;
    } else {
      $error = "Incorrect email or password.";
    }
  } catch (Exception $e) {
    $error = "Login error: " . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Whisper</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f9ff;
    }
    .login-card {
      max-width: 400px;
      margin: auto;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
    }
    .login-logo img {
      height: 60px;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #4e73df;
    }
    .btn-primary {
      background-color: #4e73df;
      border-color: #4e73df;
    }
    .btn-primary:hover {
      background-color: #2e59d9;
    }
  </style>
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card login-card p-4 w-100">
      <div class="text-center login-logo mb-4">
        <img src="/assets/img/logo/logo_em_grande/logo_corrigido.png" alt="Whisper Logo">
      </div>

      <h4 class="text-center mb-3">Login to your account</h4>
      <p class="text-center text-muted small mb-4">Enter your email and password</p>

      <!-- Error message -->
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- Form -->
      <form method="POST" action="" onsubmit="return validateForm()">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" id="email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="6Lc_Yz0rAAAAAM_ZttifOn4acP3yES6dJhi_bO-z"></div>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
        <div class="text-center mt-3">
          <p class="small">Don't have an account yet? <a href="signup.php">Create an account</a></p>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    function validateForm() {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!email || !password) {
        alert('Please fill in all fields.');
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
