<?php
require_once '../../../backend/db.php';
include '../includes/header.php'; 
include '../includes/sidebar.php'; 
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Pontos Turísticos</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Lista de Pontos Turísticos</h5>

            <!-- Botão para Adicionar Ponto -->
            <div class="mb-3 d-flex justify-content-end">
              <button class="btn btn-success btn-sm rounded d-flex align-items-center justify-content-center"
                style="height: 38px; padding-left: 10px; padding-right: 10px;" onclick="toggleForm()"
                title="Adicionar Ponto Turístico">
                <i></i> Adicionar Ponto Turístico
              </button>
            </div>

            <!-- Mensagens de Sucesso ou Erro -->
            <?php if (isset($_GET['erro'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
            <?php elseif (isset($_GET['sucesso'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
            <?php endif; ?>

            <!-- Formulário de Adicionar Ponto -->
            <form id="add-ponto-form" class="border rounded p-3 mb-4 bg-light animate__animated" method="POST"
              action="../adicionar_ponto.php" style="display: none;">
              <div class="row g-2 align-items-end">
                <div class="col-md-4">
                  <label class="form-label">Nome do Ponto</label>
                  <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Longitude</label>
                  <input type="text" name="longitude" class="form-control" required
                    pattern="^-?\d+([.,]\d+)?$" oninput="this.value = this.value.replace(',', '.')">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Latitude</label>
                  <input type="text" name="latitude" class="form-control" required
                    pattern="^-?\d+([.,]\d+)?$" oninput="this.value = this.value.replace(',', '.')">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Ponto Inicial</label>
                  <select name="ponto_inicial" class="form-control" required>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Ponto Final</label>
                  <select name="ponto_final" class="form-control" required>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                  </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
              </div>
            </form>

            <!-- Tabela de Pontos -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Longitude</th>
                  <th>Latitude</th>
                  <th>Ponto Inicial</th>
                  <th>Ponto Final</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                <?php
                try {
                  $stmt = $pdo->query("SELECT * FROM pontos ORDER BY id DESC");
                  while ($ponto = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <tr>
                  <td><?= $ponto['id'] ?></td>
                  <td><?= htmlspecialchars($ponto['nome']) ?></td>
                  <td><?= htmlspecialchars($ponto['longitude']) ?></td>
                  <td><?= htmlspecialchars($ponto['latitude']) ?></td>
                  <td><?= $ponto['ponto_inicial'] ? 'Sim' : 'Não' ?></td>
                  <td><?= $ponto['ponto_final'] ? 'Sim' : 'Não' ?></td>
                  <td class="d-flex gap-1 flex-wrap">
                    <button class="btn btn-sm btn-warning" onclick="mostrarEditar(<?= $ponto['id'] ?>)">Editar</button>
                    <form method="POST" action="../apagar_ponto.php" onsubmit="return confirm('Tens a certeza?')"
                      class="d-inline">
                      <input type="hidden" name="id" value="<?= $ponto['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>

                <!-- Formulário de Edição -->
                <tr id="editar-<?= $ponto['id'] ?>" style="display: none;">
                  <td colspan="7">
                    <form method="POST" action="../atualizar_ponto.php" class="row g-2 justify-content-center">
                      <input type="hidden" name="id" value="<?= $ponto['id'] ?>">
                      <div class="col-md-4">
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($ponto['nome']) ?>" required>
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="longitude" class="form-control" required
                               pattern="^-?\d+([.,]\d+)?$"
                               oninput="this.value = this.value.replace(',', '.')"
                               value="<?= htmlspecialchars($ponto['longitude']) ?>">
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="latitude" class="form-control" required
                               pattern="^-?\d+([.,]\d+)?$"
                               oninput="this.value = this.value.replace(',', '.')"
                               value="<?= htmlspecialchars($ponto['latitude']) ?>">
                      </div>
                      <div class="col-md-4">
                        <select name="ponto_inicial" class="form-control" required>
                          <option value="1" <?= $ponto['ponto_inicial'] ? 'selected' : '' ?>>Sim</option>
                          <option value="0" <?= !$ponto['ponto_inicial'] ? 'selected' : '' ?>>Não</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <select name="ponto_final" class="form-control" required>
                          <option value="1" <?= $ponto['ponto_final'] ? 'selected' : '' ?>>Sim</option>
                          <option value="0" <?= !$ponto['ponto_final'] ? 'selected' : '' ?>>Não</option>
                        </select>
                      </div>
                      <div class="col-md-4 d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                        <button type="button" onclick="fecharEditar(<?= $ponto['id'] ?>)"
                          class="btn btn-secondary btn-sm">Cancelar</button>
                      </div>
                    </form>
                  </td>
                </tr>
                <?php endwhile; } catch (PDOException $e) {
                  echo '<tr><td colspan="7">Erro ao carregar os pontos: ' . $e->getMessage() . '</td></tr>';
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
  const form = document.getElementById("add-ponto-form");
  form.style.display = form.style.display === "none" ? "block" : "none";
}
function mostrarEditar(id) {
  document.getElementById('editar-' + id).style.display = "table-row";
}
function fecharEditar(id) {
  document.getElementById('editar-' + id).style.display = "none";
}
</script>

<?php include '../includes/script.php'; ?>
<?php include '../includes/footer.php'; ?>