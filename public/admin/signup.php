<?php
session_start();

require_once '../../backend/db.php';
include 'includes/styles.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
    $name = trim($_POST['nome']);
    $phone = $_POST['contacto'];
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);

    // Google reCAPTCHA verification
    if (!isset($_POST['g-recaptcha-response'])) {
      throw new Exception("Please confirm you're not a robot.");
    }

    $recaptcha = $_POST['g-recaptcha-response'];
    $secretKey = '6Lc_Yz0rAAAAAKsaJ8eylk7LadyDn29OXWWzosZW';

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
      'secret' => $secretKey,
      'response' => $recaptcha
    ];

    $options = [
      'http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($data)
      ]
    ];

    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
      throw new Exception("reCAPTCHA validation failed. Please try again.");
    }

    // Field validation
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($confirmPassword)) {
      throw new Exception("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("The provided email is not valid.");
    }

    if ($password !== $confirmPassword) {
      throw new Exception("Passwords do not match.");
    }

    // Generate token and expiration
    $token = bin2hex(random_bytes(16));
    $expires_at = date('Y-m-d H:i:s', time() + 600);

    // Insert into database
    $sqlInsert = "INSERT INTO users (nome, contacto, email, password, token, expires_at, role)
                  VALUES (:nome, :contacto, :email, :password, :token, :expires_at, :role)";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->execute([
      'nome' => $name,
      'contacto' => $phone,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'token' => $token,
      'expires_at' => $expires_at,
      'role' => 3
    ]);

    header('Location: login.php');
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account - Whisper</title>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="/" class="logo d-flex align-items-center w-auto">
                  <img src="/assets/img/logo/logo_em_grande/logo_corrigido.png" alt="Whisper Logo">
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create Account</h5>
                    <p class="text-center small">Enter your personal details to create an account</p>
                  </div>

                  <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                      <?= htmlspecialchars($error) ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  <?php endif; ?>

                  <form method="POST" action="" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="yourName" class="form-label">Name</label>
                      <input type="text" name="nome" class="form-control" id="yourName" required>
                      <div class="invalid-feedback">Please enter your name!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" id="yourEmail" required>
                      <div class="invalid-feedback">Please enter a valid email!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPhone" class="form-label">Phone</label>
                      <input type="text" name="contacto" class="form-control" id="yourPhone" required>
                      <div class="invalid-feedback">Please enter your phone number!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourConfirmPassword" class="form-label">Confirm Password</label>
                      <input type="password" name="confirm-password" class="form-control" id="yourConfirmPassword" required>
                      <div class="invalid-feedback">Please confirm your password!</div>
                    </div>

                    <!-- Google reCAPTCHA -->
                    <div class="col-12">
                      <div class="g-recaptcha" data-sitekey="6Lc_Yz0rAAAAAM_ZttifOn4acP3yES6dJhi_bO-z"></div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Create Account</button>
                    </div>
                    <div class="text-center mt-3">
                      <p class="small">Already have an account? <a href="../admin/login.php">Log on your account</a></p>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Scripts -->
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-hide alert after 5 seconds
    setTimeout(() => {
      const alert = document.getElementById('error-alert');
      if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
      }
    }, 5000);
  </script>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
