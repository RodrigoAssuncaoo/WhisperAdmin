<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include '../backend/db.php';
// include '../backend/send_email.php'; // Descomenta apenas se o ficheiro existir

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);

    if ($password !== $confirmPassword) {
      throw new Exception("As passwords não coincidem.");
    }

    $sqlInsert = 'INSERT INTO utilizadores (nome, telefone, email, password)
                  VALUES (:nome, :telefone, :email, :password)';
    $stmt = $PDO->prepare($sqlInsert);
    $stmt->execute([
      'name' => $nome,
      'telefone' => $telefone,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    $userId = $PDO->lastInsertId();
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 60000);

    $sqlInsertToken = 'INSERT INTO signup_tokens (user_id, token, expires_at)
                      VALUES (:user_id, :token, :expires)';
    $stmt = $PDO->prepare($sqlInsertToken);
    $stmt->execute(['user_id' => $userId, 'token' => $token, 'expires' => $expires]);

    // sendVerificationEmail($email, $token); // Apenas se tiveres o ficheiro e a função
    header('Location: login.html');
    exit;

  } catch (Exception $e) {
    echo "Erro ao criar conta: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<body>

  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="https://rodrigoassuncaoo.github.io/WhisperSite/assets/img/logo/logo%20pequena/favicon.ico" alt="">
                  <span class="d-none d-lg-block">NiceAdmin</span>
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form method="POST" action="" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="yourName" class="form-label">Your Name</label>
                      <input type="text" name="name" class="form-control" id="yourName" required>
                      <div class="invalid-feedback">Please, enter your name!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Your Email</label>
                      <input type="email" name="email" class="form-control" id="yourEmail" required>
                      <div class="invalid-feedback">Please enter a valid Email address!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPhone" class="form-label">Phone</label>
                      <input type="text" name="telefone" class="form-control" id="yourPhone" required>
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

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="pages-login.html">Log in</a></p>
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <?php include 'includes/script.php' ?>
  <?php include 'includes/footer.php' ?>

</body>
</html>
