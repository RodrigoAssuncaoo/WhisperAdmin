<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php require_once '../../backend/connection.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Utilizadores</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Lista de Utilizadores</h5>

            <table class="table datatable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Contacto</th>
                  <th>Função</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $resultado = $connection->query("SELECT * FROM users");
                $roles = [1 => 'Admin', 2 => 'Guia', 3 => 'Cliente'];
                while($user = $resultado->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($user['nome']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['contacto']) ?></td>
                    <td id="role-text-<?= $user['id'] ?>"> <?= $roles[$user['role']] ?? 'Desconhecido' ?> </td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary" onclick="mostrarFormulario(<?= $user['id'] ?>)">Alterar Role</button>
                      <form id="form-role-<?= $user['id'] ?>" method="POST" action="atualizar_role.php" style="display: none; margin-top: 10px;">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                        <select name="role" class="form-select form-select-sm d-inline-block w-auto">
                          <option value="1" <?= $user['role']==1 ? 'selected' : '' ?>>Admin</option>
                          <option value="2" <?= $user['role']==2 ? 'selected' : '' ?>>Guia</option>
                          <option value="3" <?= $user['role']==3 ? 'selected' : '' ?>>Cliente</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">Guardar</button>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="esconderFormulario(<?= $user['id'] ?>)">Cancelar</button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
function mostrarFormulario(id) {
  document.getElementById('form-role-' + id).style.display = 'block';
}
function esconderFormulario(id) {
  document.getElementById('form-role-' + id).style.display = 'none';
}
</script>

<?php include 'includes/script.php'; ?>
<?php include 'includes/footer.php'; ?>
