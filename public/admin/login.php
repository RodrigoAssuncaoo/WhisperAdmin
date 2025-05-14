<?php 
session_start();

require_once '../../backend/db.php';

// Se já está logado, redireciona
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['is_admin']) {
        header('Location: /admin/index.php');
    } else {
        header('Location: /'); // 
    }
    exit();
}

$erro = "";

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Guardar dados na sessão
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['is_admin'] = $user['isAdmin'];

                // Redirecionar consoante isAdmin
                if ($user['isAdmin']) {
                    header('Location: /admin/index.php');
                } else {
                    header('Location: /'); // Página pública principal
                }
                exit();
            } else {
                $erro = "Email ou palavra-passe incorretos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao tentar fazer login: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Whisper</title>
  <link rel="stylesheet" href="assets/css/style.css">
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

                  <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger" role="alert">
                      <?= htmlspecialchars($erro) ?>
                    </div>
                  <?php endif; ?>

                  <!-- FORMULÁRIO CORRIGIDO -->
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

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Entrar</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Ainda não tem conta? <a href="pages-register.html">Crie uma</a></p>
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
