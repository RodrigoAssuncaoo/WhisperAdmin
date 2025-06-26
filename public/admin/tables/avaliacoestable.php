<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../../../backend/db.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Avaliações</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lista de Avaliações</h5>

                        <!-- Mensagens -->
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
                        <?php elseif (isset($_GET['sucesso'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
                        <?php endif; ?>

                        <!-- Tabela -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilizador</th>
                                    <th>Avaliação</th>
                                    <th>Comentário</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("
                                        SELECT a.id, a.avaliacao_roteiro, a.comentario, u.nome AS utilizador
                                        FROM avaliacoes a
                                        JOIN users u ON a.user_id = u.id
                                        ORDER BY a.id DESC
                                    ");
                                    while ($avaliacao = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                    <tr>
                                        <td><?= $avaliacao['id'] ?></td>
                                        <td><?= htmlspecialchars($avaliacao['utilizador']) ?></td>
                                        <td><?= $avaliacao['avaliacao_roteiro'] ?></td>
                                        <td><?= htmlspecialchars($avaliacao['comentario']) ?></td>
                                        <td class="d-flex gap-1 flex-wrap">
                                            <button class="btn btn-sm btn-warning" onclick="mostrarEditar(<?= $avaliacao['id'] ?>)">Editar</button>
                                            <form method="POST" action="../apagar_avaliacao.php" onsubmit="return confirm('Tens a certeza?')" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $avaliacao['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Formulário de edição -->
                                    <tr id="editar-<?= $avaliacao['id'] ?>" style="display: none;">
                                        <td colspan="5">
                                            <form method="POST" action="../atualizar_avaliacao.php" class="row g-2 justify-content-center">
                                                <input type="hidden" name="id" value="<?= $avaliacao['id'] ?>">
                                                <div class="col-md-3">
                                                    <input type="number" name="avaliacao_roteiro" class="form-control" value="<?= $avaliacao['avaliacao_roteiro'] ?>" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="comentario" class="form-control" value="<?= htmlspecialchars($avaliacao['comentario']) ?>" required>
                                                </div>
                                                <div class="col-md-4 d-flex gap-1">
                                                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                                    <button type="button" onclick="fecharEditar(<?= $avaliacao['id'] ?>)" class="btn btn-secondary btn-sm">Cancelar</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile;
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="5">Erro ao carregar avaliações: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
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
    function mostrarEditar(id) {
        document.getElementById('editar-' + id).style.display = 'table-row';
    }

    function fecharEditar(id) {
        document.getElementById('editar-' + id).style.display = 'none';
    }
</script>

<?php include '../includes/script.php'; ?>
<?php include '../includes/footer.php'; ?>
