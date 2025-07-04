<?php
// pages/users.php

include '../includes/header.php';
include '../includes/sidebar.php';
require_once '../../../backend/db.php';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Users</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">User List</h5>

            <!-- FILTROS E BOTÃO ADD -->
            <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="btn-group">
                <button type="button"
                        class="btn btn-outline-primary btn-sm filtro-btn active"
                        data-role="todos"
                        onclick="filtrarRole('todos')">
                  Todos
                </button>
                <button type="button"
                        class="btn btn-outline-success btn-sm filtro-btn"
                        data-role="admin"
                        onclick="filtrarRole('Admin')">
                  Admin
                </button>
                <button type="button"
                        class="btn btn-outline-warning btn-sm filtro-btn"
                        data-role="guia"
                        onclick="filtrarRole('Guia')">
                  Guia
                </button>
                <button type="button"
                        class="btn btn-outline-info btn-sm filtro-btn"
                        data-role="cliente"
                        onclick="filtrarRole('Cliente')">
                  Cliente
                </button>
              </div>
              <button class="btn btn-success btn-sm rounded d-flex align-items-center justify-content-center"
                      onclick="toggleForm()" title="Add User">
                <i class="fas fa-plus"></i>&nbsp;Add User
              </button>
            </div>

            <!-- MENSAGENS -->
            <?php if (isset($_GET['erro'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
            <?php elseif (isset($_GET['sucesso'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
            <?php endif; ?>

            <!-- FORM ADD -->
            <form id="add-user-form"
                  class="border rounded p-3 mb-4 bg-light"
                  method="POST"
                  action="../adicionar_user.php"
                  style="display: none;"
                  onsubmit="return validarFormulario()">
              <div class="row g-2 align-items-end">
                <div class="col-md-3">
                  <label class="form-label">Name</label>
                  <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Phone</label>
                  <input type="text" name="contacto" id="contacto" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Role</label>
                  <select name="role" id="role" class="form-select" required>
                    <option value="">Escolha...</option>
                    <option value="1">Admin</option>
                    <option value="2">Guia</option>
                    <option value="3">Cliente</option>
                  </select>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary mt-2">Save</button>
                </div>
              </div>
            </form>

            <!-- TABELA -->
            <table id="userstable" class="table datatable">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // Array em Português
                  $roles = [1=>'Admin',2=>'Guia',3=>'Cliente'];

                  try {
                    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
                    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
                      $r = in_array($user['role'], [1,2,3]) ? $user['role'] : 3;
                ?>
                <tr class="user-row" data-role="<?= strtolower($roles[$r]) ?>">
                  <td><?= htmlspecialchars($user['nome']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><?= htmlspecialchars($user['contacto']) ?></td>
                  <td><?= $roles[$r] ?></td>
                  <td class="d-flex gap-1 flex-wrap">
                    <button class="btn btn-sm btn-warning"
                            onclick="mostrarEditar(<?= $user['id'] ?>)">
                      Edit
                    </button>
                    <form method="POST"
                          action="../apagar_user.php"
                          onsubmit="return confirm('Are you sure?')" class="d-inline">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>

                <!-- EDIÇÃO -->
                <tr id="editar-<?= $user['id'] ?>" style="display: none;">
                  <td colspan="5" class="text-center">
                    <form method="POST"
                          action="../atualizar_user.php"
                          class="row justify-content-center g-2">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <div class="col-md-2">
                        <input type="text" name="nome" class="form-control"
                               value="<?= htmlspecialchars($user['nome']) ?>" required>
                      </div>
                      <div class="col-md-2">
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                      </div>
                      <div class="col-md-2">
                        <input type="text" name="contacto" class="form-control"
                               value="<?= htmlspecialchars($user['contacto']) ?>" required>
                      </div>
                      <div class="col-md-2">
                        <select name="role" class="form-select" required>
                          <option value="1" <?= $r===1?'selected':'' ?>>Admin</option>
                          <option value="2" <?= $r===2?'selected':'' ?>>Guia</option>
                          <option value="3" <?= $r===3?'selected':'' ?>>Cliente</option>
                        </select>
                      </div>
                      <div class="col-md-4 d-flex gap-1 justify-content-center">
                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                        <button type="button"
                                onclick="fecharEditar(<?= $user['id'] ?>)"
                                class="btn btn-secondary btn-sm">
                          Cancel
                        </button>
                      </div>
                    </form>
                  </td>
                </tr>
                <?php
                    endwhile;
                  } catch (PDOException $e) {
                    echo '<tr><td colspan="5">Error loading users.</td></tr>';
                  }
                ?>
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
    form.style.display = (form.style.display === 'none' || !form.style.display)
                         ? 'block' : 'none';
  }

  function mostrarEditar(id) {
    document.getElementById('editar-' + id).style.display = 'table-row';
  }

  function fecharEditar(id) {
    document.getElementById('editar-' + id).style.display = 'none';
  }

  function validarFormulario() {
    const nome     = document.getElementById("nome").value.trim();
    const email    = document.getElementById("email").value.trim();
    const contacto = document.getElementById("contacto").value.trim();
    const password = document.getElementById("password").value.trim();
    const role     = document.getElementById("role").value;
    if (!nome||!email||!contacto||!password||!role) {
      alert("All fields are required."); return false;
    }
    if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nome)) {
      alert("Invalid name."); return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alert("Invalid email."); return false;
    }
    if (!/^\d{9}$/.test(contacto)) {
      alert("Invalid phone number."); return false;
    }
    if (password.length < 6) {
      alert("Weak password."); return false;
    }
    return true;
  }

  function filtrarRole(role) {
    const rows    = document.querySelectorAll('#userstable .user-row');
    const buttons = document.querySelectorAll('.filtro-btn');
    rows.forEach(row => {
      const r = row.dataset.role.toLowerCase();
      row.style.display = (role === 'todos' || r === role.toLowerCase())
                          ? '' : 'none';
    });
    buttons.forEach(btn => {
      btn.classList.toggle('active',
        btn.dataset.role === role.toLowerCase()
      );
    });
    localStorage.setItem('filtroRole', role);
  }

  window.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('filtroRole') || 'todos';
    filtrarRole(saved);
  });
</script>

<?php
include '../includes/script.php';
include '../includes/footer.php';
?>
