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
?><!DOCTYPE html>
<html lang="pt">
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
      width: 100%;
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
    <div class="card login-card p-4">
      
      <div class="text-center login-logo mb-4">
        <img src="/assets/img/logo/logo_em_grande/logo_corrigido.png" alt="Whisper Logo">
      </div>

      <h4 class="text-center mb-3">Login na sua conta</h4>
      <p class="text-center text-muted small mb-4">Introduza o seu email e palavra-passe</p>

      <!-- Formulário de Login -->
      <form method="POST" action="" onsubmit="return validarFormulario()">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" id="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Palavra-passe</label>
          <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <!-- Google reCAPTCHA -->
        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="6Lc_Yz0rAAAAAM_ZttifOn4acP3yES6dJhi_bO-z"></div>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Entrar</button>
        </div>

        <div class="text-center mt-3">
          <p class="small">Ainda não tem conta? <a href="signup.php">Crie uma conta</a></p>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    function validarFormulario() {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      if (email === '' || password === '') {
        alert('Preencha todos os campos.');
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
