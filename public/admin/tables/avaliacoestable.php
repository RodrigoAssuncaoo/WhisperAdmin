<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../../../backend/db.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Reviews</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Review List</h5>

                        <!-- Error or success messages -->
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
                        <?php elseif (isset($_GET['sucesso'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
                        <?php endif; ?>

                        <!-- Button to add review -->
                        <div class="mb-3 d-flex justify-content-end">
                            <button class="btn btn-success btn-sm rounded d-flex align-items-center justify-content-center"
                                    onclick="toggleAddForm()" title="Add Review">
                                <i></i> Add Review
                            </button>
                        </div>

                        <!-- Add Review Form -->
                        <form id="add-avaliacao-form" class="border rounded p-3 mb-4 bg-light" method="POST" action="../adicionar_avaliacao.php" style="display: none;">
                            <div class="row g-2">
                                <!-- Select user field -->
                                <div class="col-md-4">
                                    <label class="form-label">User</label>
                                    <select name="user_id" class="form-select" required>
                                        <?php
                                        $stmtUsers = $pdo->query("SELECT id, nome FROM users ORDER BY nome ASC");
                                        while ($user = $stmtUsers->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $user['id'] . "'>" . htmlspecialchars($user['nome']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Select route field -->
                                <div class="col-md-4">
                                    <label class="form-label">Route</label>
                                    <select name="id_roteiro" class="form-select" required>
                                        <?php
                                        $stmtRoteiros = $pdo->query("SELECT id, nome FROM roteiros ORDER BY nome ASC");
                                        while ($roteiro = $stmtRoteiros->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $roteiro['id'] . "'>" . htmlspecialchars($roteiro['nome']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Rating field -->
                                <div class="col-md-2">
                                    <label class="form-label">Rating</label>
                                    <input type="number" name="avaliacao_roteiro" class="form-control" min="1" max="10" required>
                                </div>

                                <!-- Comment field -->
                                <div class="col-md-4">
                                    <label class="form-label">Comment</label>
                                    <input type="text" name="comentario" class="form-control" required>
                                </div>

                                <!-- Submit button -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                </div>
                            </div>
                        </form>

                        <!-- Reviews table -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Route</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("
                                        SELECT a.id, a.avaliacao_roteiro, a.comentario, u.nome AS utilizador, r.nome AS roteiro
                                        FROM avaliacoes a
                                        JOIN users u ON a.user_id = u.id
                                        JOIN roteiros r ON a.id_roteiro = r.id
                                        ORDER BY a.id DESC
                                    ");
                                    while ($avaliacao = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                    <tr>
                                        <td><?= $avaliacao['id'] ?></td>
                                        <td><?= htmlspecialchars($avaliacao['utilizador']) ?></td>
                                        <td><?= htmlspecialchars($avaliacao['roteiro']) ?></td>
                                        <td><?= $avaliacao['avaliacao_roteiro'] ?></td>
                                        <td><?= htmlspecialchars($avaliacao['comentario']) ?></td>
                                        <td class="d-flex gap-1 flex-wrap">
                                            <button class="btn btn-sm btn-warning" onclick="mostrarEditar(<?= $avaliacao['id'] ?>)">Edit</button>
                                            <form method="POST" action="../apagar_avaliacao.php" onsubmit="return confirm('Are you sure?')" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $avaliacao['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit form -->
                                    <tr id="editar-<?= $avaliacao['id'] ?>" style="display: none;">
                                        <td colspan="6">
                                            <form method="POST" action="../atualizar_avaliacao.php" class="row g-2 justify-content-center">
                                                <input type="hidden" name="id" value="<?= $avaliacao['id'] ?>">
                                                <div class="col-md-3">
                                                    <input type="number" name="avaliacao_roteiro" class="form-control" value="<?= $avaliacao['avaliacao_roteiro'] ?>" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="comentario" class="form-control" value="<?= htmlspecialchars($avaliacao['comentario']) ?>" required>
                                                </div>
                                                <div class="col-md-4 d-flex gap-1">
                                                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                                                    <button type="button" onclick="fecharEditar(<?= $avaliacao['id'] ?>)" class="btn btn-secondary btn-sm">Cancel</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile;
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="6">Error loading reviews: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
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
    function toggleAddForm() {
        const form = document.getElementById('add-avaliacao-form');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }

    function mostrarEditar(id) {
        document.getElementById('editar-' + id).style.display = 'table-row';
    }

    function fecharEditar(id) {
        document.getElementById('editar-' + id).style.display = 'none';
    }
</script>

<?php 
include 'includes/footer.php';
include 'includes/script.php';
?>
