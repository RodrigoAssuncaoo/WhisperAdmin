<?php
session_start();
var_dump($_SESSION);

require_once '../../backend/db.php';
include 'includes/styles.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
    $nome = trim($_POST['nome']);
    $contacto = $_POST['contacto'];
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    /*//----------------capcha google-------------------------------------------
    // Verificar reCAPTCHA
    if (!isset($_POST['g-recaptcha-response'])) {
      throw new Exception("Por favor confirme que não é um robô.");
    }

    $recaptcha = $_POST['g-recaptcha-response'];
    $secretKey = '6Lc_Yz0rAAAAAKsaJ8eylk7LadyDn29OXWWzosZW'; // <- Substitui pela tua chave secreta

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
    //-----------------------------validacoes---------------------------------------------------------------
    if (!$captcha_success->success) {
      throw new Exception("Validação reCAPTCHA falhou. Tente novamente.");
    }*/

    // Validação de campos obrigatórios
    if (empty($nome) || empty($contacto) || empty($email) || empty($password) || empty($confirmPassword)) {
      throw new Exception("Todos os campos são obrigatórios.");
    }

    // Validação do email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("O email inserido não é válido.");
    }

      // Confirmar password
    if ($password !== $confirmPassword) {
      throw new Exception("As passwords não coincidem.");
    }
    //-----------------------------------------------------------------------------------------------
    // Geração de token e validade
    $token = bin2hex(random_bytes(16));
    $expires_at = date('Y-m-d H:i:s', time() + 600);

    // Inserção no banco
    $sqlInsert = "INSERT INTO users (nome, contacto, email, password, token, expires_at, role)
                  VALUES (:nome, :contacto, :email, :password, :token, :expires_at, :role)";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->execute([
      'nome' => $nome,
      'contacto' => $contacto,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'token' => $token,
      'expires_at' => $expires_at,
      'role' => 3
    ]);

    header('Location: login.php');
    exit;
  } catch (Exception $e) {
    $erro = $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Criar Conta - Whisper</title>
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
                  <img src="/assets/img/logo.png" alt="Logo Whisper">
                  <span class="d-none d-lg-block">Whisper</span>
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Criar Conta</h5>
                    <p class="text-center small">Introduza os seus dados pessoais para criar uma conta</p>
                  </div>

                  <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="erro-alerta">
                      <?= htmlspecialchars($erro) ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                  <?php endif; ?>

                  <form method="POST" action="" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="yourName" class="form-label">Nome</label>
                      <input type="text" name="nome" class="form-control" id="yourName" required>
                      <div class="invalid-feedback">Por favor, insira o seu nome!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" id="yourEmail" required>
                      <div class="invalid-feedback">Por favor, insira um email válido!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPhone" class="form-label">Contacto</label>
                      <input type="text" name="contacto" class="form-control" id="yourPhone" required>
                      <div class="invalid-feedback">Por favor, insira o seu contacto!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Palavra-passe</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Por favor, insira a sua palavra-passe!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourConfirmPassword" class="form-label">Confirmar Palavra-passe</label>
                      <input type="password" name="confirm-password" class="form-control" id="yourConfirmPassword" required>
                      <div class="invalid-feedback">Por favor, confirme a sua palavra-passe!</div>
                    </div>

                    <!-- Google reCAPTCHA -->
                    <div class="col-12">
                      <div class="g-recaptcha" data-sitekey="6Lc_Yz0rAAAAAM_ZttifOn4acP3yES6dJhi_bO-z"></div> <!-- <- Substitui pela tua site key -->
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Criar Conta</button>
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
    // Fechar automaticamente alerta após 5 segundos
    setTimeout(() => {
      const alerta = document.getElementById('erro-alerta');
      if (alerta) {
        alerta.classList.remove('show');
        alerta.classList.add('fade');
      }
    }, 5000);
  </script>

  <?php include 'includes/footer.php'; ?>
</body>

</html>