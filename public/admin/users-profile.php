<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php 



?>

<main id="main" class="main">
  <div class="container">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">User Profile</h1>
    <form method="POST">
      <div class="mb-3">
        <label>Nome de Utilizador</label>
        <input type="text" name="username" value="<?php echo $_SESSION['user']['username']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contacto</label>
        <input type="text" name="contact" value="<?php echo $_SESSION['user']['contact']; ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="password" class="form-control">
      </div>
      <button class="btn btn-primary">Guardar</button>
    </form>
  </div>
</main>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
