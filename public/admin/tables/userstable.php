<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../../../backend/db.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

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

            <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm filtro-btn" data-role="todos" onclick="filtrarRole('todos')">Todos</button>
                <button class="btn btn-outline-success btn-sm filtro-btn" data-role="admin" onclick="filtrarRole('Admin')">Admin</button>
                <button class="btn btn-outline-warning btn-sm filtro-btn" data-role="guia" onclick="filtrarRole('Guia')">Guia</button>
                <button class="btn btn-outline-info btn-sm filtro-btn" data-role="cliente" onclick="filtrarRole('Cliente')">Cliente</button>
              </div>
              <button class="btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center"
                      style="width: 38px; height: 38px;" onclick="toggleForm()" title="Adicionar Utilizador">
                <i class="fas fa-plus"></i>
              </button>
            </div>

            <?php if (isset($_GET['erro'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
            <?php elseif (isset($_GET['sucesso'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
            <?php endif; ?>

            <!-- Formulário adicionar -->
            <form id="add-user-form" class="border rounded p-3 mb-4 bg-light animate__animated"
                  method="POST" action="../adicionar_user.php" style="display: none;" onsubmit="return validarFormulario()">
              <div class="row g-2 align-items-end">
                <div class="col-md-3">
                  <label class="form-label">Nome</label>
                  <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Contacto</label>
                  <input type="text" name="contacto" id="contacto" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Palavra-passe</label>
                  <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Função</label>
                  <select name="role" id="role" class="form-select" required>
                    <option value="">Escolher...</option>
                    <option value="1">Admin</option>
                    <option value="2">Guia</option>
                    <option value="3">Cliente</option>
                  </select>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary mt-2">Guardar</button>
                </div>
              </div>
            </form>

            <!-- Tabela -->
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
                $roles = [1 => 'Admin', 2 => 'Guia', 3 => 'Cliente'];
                try {
                  $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
                  while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
                    $role = isset($user['role']) && in_array($user['role'], [1,2,3]) ? $user['role'] : 3;
                ?>
                <tr class="user-row" data-role="<?= strtolower($roles[$role]) ?>">
                  <td><?= htmlspecialchars($user['nome']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><?= htmlspecialchars($user['contacto']) ?></td>
                  <td><?= $roles[$role] ?></td>
                  <td class="d-flex gap-1 flex-wrap">
                    <button class="btn btn-sm btn-warning" onclick="mostrarEditar(<?= $user['id'] ?>)">Editar</button>
                    <form method="POST" action="../apagar_user.php" onsubmit="return confirm('Tens a certeza?')" class="d-inline">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>

                <!-- Formulário de edição centralizado -->
                <tr id="editar-<?= $user['id'] ?>" style="display: none;">
                  <td colspan="5" class="text-center">
                    <form method="POST" action="../atualizar_user.php" class="row justify-content-center g-2">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <div class="col-md-2">
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required>
                      </div>
                      <div class="col-md-2">
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                      </div>
                      <div class="col-md-2">
                        <input type="text" name="contacto" class="form-control" value="<?= htmlspecialchars($user['contacto']) ?>" required>
                      </div>
                      <div class="col-md-2">
                        <select name="role" class="form-select" required>
                          <option value="1" <?= $role == 1 ? 'selected' : '' ?>>Admin</option>
                          <option value="2" <?= $role == 2 ? 'selected' : '' ?>>Guia</option>
                          <option value="3" <?= $role == 3 ? 'selected' : '' ?>>Cliente</option>
                        </select>
                      </div>
                      <div class="col-md-4 d-flex gap-1 justify-content-center">
                        <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                        <button type="button" onclick="fecharEditar(<?= $user['id'] ?>)" class="btn btn-secondary btn-sm">Cancelar</button>
                      </div>
                    </form>
                  </td>
                </tr>
                <?php endwhile; } catch (PDOException $e) {
                  echo '<tr><td colspan="5">Erro ao carregar utilizadores.</td></tr>';
                } ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
function toggleForm() {
  const form = document.getElementById('add-user-form');
  if (form.style.display === 'none' || form.style.display === '') {
    form.style.display = 'block';
    form.classList.remove('animate__fadeOutUp');
    form.classList.add('animate__fadeInDown');
  } else {
    form.classList.remove('animate__fadeInDown');
    form.classList.add('animate__fadeOutUp');
    setTimeout(() => form.style.display = 'none', 500);
  }
}

function mostrarEditar(id) {
  document.getElementById('editar-' + id).style.display = 'table-row';
}

function fecharEditar(id) {
  document.getElementById('editar-' + id).style.display = 'none';
}

function validarFormulario() {
  const nome = document.getElementById("nome").value.trim();
  const email = document.getElementById("email").value.trim();
  const contacto = document.getElementById("contacto").value.trim();
  const password = document.getElementById("password").value.trim();
  const role = document.getElementById("role").value;

  if (!nome || !email || !contacto || !password || !role) {
    alert("Todos os campos são obrigatórios.");
    return false;
  }

  if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nome)) {
    alert("Nome inválido.");
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    alert("Email inválido.");
    return false;
  }

  if (!/^\d{9}$/.test(contacto)) {
    alert("Contacto inválido.");
    return false;
  }

  if (password.length < 6) {
    alert("Password fraca.");
    return false;
  }

  return true;
}

function filtrarRole(role) {
  const linhas = document.querySelectorAll('.user-row');
  const botoes = document.querySelectorAll('.filtro-btn');
  linhas.forEach(l => {
    const r = l.dataset.role.toLowerCase();
    l.style.display = (role === 'todos' || r === role.toLowerCase()) ? '' : 'none';
  });
  botoes.forEach(btn => {
    btn.classList.remove('active');
    if (btn.dataset.role === role.toLowerCase()) btn.classList.add('active');
  });
  localStorage.setItem('filtroRole', role);
}

window.addEventListener('DOMContentLoaded', () => {
  const filtroGuardado = localStorage.getItem('filtroRole') || 'todos';
  filtrarRole(filtroGuardado);
});
</script>

<?php include '../includes/script.php'; ?>
<?php include '../includes/footer.php'; ?>
