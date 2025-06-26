<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../../../backend/db.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Roteiros</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lista de Roteiros</h5>

                        <!-- Botão Adicionar -->
                        <div class="mb-3 d-flex justify-content-end">
                            <button class="btn btn-success btn-sm rounded d-flex align-items-center justify-content-center"
                                style="height: 38px; padding-left: 10px; padding-right: 10px;" onclick="toggleForm()" title="Adicionar Roteiro">
                                <i></i> Adicionar Roteiro
                            </button>
                        </div>

                        <!-- Mensagens -->
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
                        <?php elseif (isset($_GET['sucesso'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
                        <?php endif; ?>

                        <!-- Formulário adicionar -->
                        <form id="add-roteiro-form" class="border rounded p-3 mb-4 bg-light animate__animated"
                            method="POST" action="../adicionar_roteiro.php" style="display: none;">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Nome do Roteiro</label>
                                    <input type="text" name="nome" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ID Tipo Roteiro</label>
                                    <input type="number" name="id_tipo_roteiro" class="form-control" required>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                                </div>

                                <!-- NOVO CAMPO: Pontos turísticos -->
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Pontos do Roteiro</label>
                                    <select name="pontos[]" class="form-select" multiple required>
                                        <?php
                                        try {
                                            $pontos = $pdo->query("SELECT id, nome FROM pontos ORDER BY nome ASC")->fetchAll();
                                            foreach ($pontos as $ponto) {
                                                echo "<option value='{$ponto['id']}'>" . htmlspecialchars($ponto['nome']) . "</option>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "<option disabled>Erro ao carregar pontos</option>";
                                        }
                                        ?>
                                    </select>
                                    <small class="text-muted">Seleciona vários com Ctrl (Windows) ou Cmd (Mac).</small>
                                </div>
                            </div>
                        </form>

                        <!-- Tabela -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Tipo Roteiro</th>
                                    <th>Nome</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT * FROM roteiros ORDER BY id DESC");
                                    while ($roteiro = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                        <tr>
                                            <td><?= $roteiro['id'] ?></td>
                                            <td><?= $roteiro['id_tipo_roteiro'] ?></td>
                                            <td><?= htmlspecialchars($roteiro['nome']) ?></td>
                                            <td class="d-flex gap-1 flex-wrap">
                                                <button class="btn btn-sm btn-warning" onclick="mostrarEditar(<?= $roteiro['id'] ?>)">Editar</button>
                                                <form method="POST" action="../apagar_roteiro.php" onsubmit="return confirm('Tens a certeza?')" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $roteiro['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Formulário edição -->
                                        <tr id="editar-<?= $roteiro['id'] ?>" style="display: none;">
                                            <td colspan="4">
                                                <form method="POST" action="../atualizar_roteiro.php" class="row g-2 justify-content-center">
                                                    <input type="hidden" name="id" value="<?= $roteiro['id'] ?>">
                                                    <div class="col-md-4">
                                                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($roteiro['nome']) ?>" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="id_tipo_roteiro" class="form-control" value="<?= $roteiro['id_tipo_roteiro'] ?>" required>
                                                    </div>
                                                    <div class="col-md-4 d-flex gap-1">
                                                        <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                                        <button type="button" onclick="fecharEditar(<?= $roteiro['id'] ?>)" class="btn btn-secondary btn-sm">Cancelar</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="4">Erro ao carregar roteiros.</td></tr>';
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
        const form = document.getElementById('add-roteiro-form');
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
</script>

<?php include '../includes/script.php'; ?>
<?php include '../includes/footer.php'; ?>
