<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLogin = isset($_SESSION['user_id']);
$nome = $_SESSION['user']['nome'] ?? 'Utilizador';
$roleInt = $_SESSION['user']['role'] ?? 3;
$roles = [1 => 'Admin', 2 => 'Guia', 3 => 'Cliente'];
$roleNome = $roles[$roleInt] ?? 'Desconhecido';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Whisper</title>

  <link href="/assets/img/logo/logo_pequena/favicon.ico" rel="icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">

  <style>
    .logo-img {
      max-height: 40px;
      width: auto;
    }
    .logo-toggle-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
    }
  </style>
</head>

<body>
  <header id="header" class="header fixed-top d-flex align-items-center justify-content-between px-3">
    <div class="logo-toggle-wrapper">
      <i class="bi bi-list toggle-sidebar-btn fs-4" style="cursor: pointer;"></i>
        <a class="nav-link <?= $currentPage === 'index.php' ? '' : 'collapsed' ?>" href="/admin/index.php">
        <img src="/assets/img/logo/logo_em_grande/logo_corrigido.png" alt="Whisper" class="logo-img">
      </a>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center mb-0">
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= htmlspecialchars($nome) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header text-center">
              <h6><?= htmlspecialchars($nome) ?></h6>
              <span><?= htmlspecialchars($roleNome) ?></span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="/admin/profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="login.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Restante conteúdo da página aqui -->

</body>
</html>
