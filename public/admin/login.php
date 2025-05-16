<?php
session_start();
require_once '../../backend/db.php';
include 'includes/styles.php';
// var_dump($_SESSION); // Podes ativar isto só para testes

// SECRET KEY do reCAPTCHA
$secretKey = "6Lc_Yz0rAAAAAKsaJ8eylk7LadyDn29OXWWzosZW";

// Lógica de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verifica se o reCAPTCHA foi submetido
  if (!isset($_POST['g-recaptcha-response'])) {
    $erro = "Por favor confirma que não és um robô.";
  } else {
    // Verificação do reCAPTCHA com Google
    $captchaResponse = $_POST['g-recaptcha-response'];
    $remoteIP = $_SERVER['REMOTE_ADDR'];

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
      'secret' => $secretKey,
      'response' => $captchaResponse,
      'remoteip' => $remoteIP
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
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
      // CAPTCHA válido, continua com o login
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);

      try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
          // Autenticação bem-sucedida
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['nome'] = $user['nome'];
          header('Location: index.php');
          exit;
        } else {
          $erro = "Email ou palavra-passe incorretos.";
        }
      } catch (Exception $e) {
        $erro = "Erro no login: " . $e->getMessage();
      }
    } else {
      $erro = "Falha na verificação CAPTCHA. Tenta novamente.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt">

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="/" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="Whisper Logo">
                  <span class="d-none d-lg-block">Whisper</span>
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login na sua conta</h5>
                    <p class="text-center small">Introduza o seu email e palavra-passe</p>
                  </div>

                  <?php if (isset($erro)): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
                  <?php endif; ?>

                  <form class="row g-3 needs-validation" method="POST" action="" novalidate>
                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" id="yourEmail" required>
                      <div class="invalid-feedback">Introduza um email válido.</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Palavra-passe</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Introduza a sua palavra-passe.</div>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="col-12">
                      <div class="g-recaptcha" data-sitekey="6Lc_Yz0rAAAAAM_ZttifOn4acP3yES6dJhi_bO-z"></div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Entrar</button>
                    </div>

                    <div class="col-12">
                      <p class="small mb-0">Ainda não tem conta? <a href="signup.php">Crie uma conta</a></p>
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

  <!-- reCAPTCHA script -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <?php include 'includes/script.php' ?>
  <?php include 'includes/footer.php' ?>
</body>

</html>
