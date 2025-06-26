<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../../../backend/db.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

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

                        <div class="mb-3 d-flex justify-content-between flex-wrap gap-2">
                            <a href="../adicionar_roteiro.php" class="btn btn-success btn-sm rounded d-flex align-items-center justify-content-center">
                                <i class="fa fa-plus me-1"></i> Adicionar Roteiro
                            </a>
                        </div>

                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
                        <?php elseif (isset($_GET['sucesso'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
                        <?php endif; ?>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT r.id, r.nome, tr.nome AS tipo, r.picPath FROM roteiros r JOIN tipo_roteiros tr ON r.id_tipo_roteiro = tr.id ORDER BY r.id DESC");
                                    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($r['nome']) ?></td>
                                            <td><?= htmlspecialchars($r['tipo']) ?></td>
                                            <td class="d-flex gap-1 flex-wrap">
                                                <a href="../detalhes_roteiro.php?id=<?= $r['id'] ?>" class="btn btn-info btn-sm">Detalhes</a>
                                                <a href="../atualizar_roteiro.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                                <a href="../apagar_roteiro.php?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja apagar este roteiro?')">Eliminar</a>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="4">Erro ao carregar os roteiros.</td></tr>';
                                } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/script.php'; ?>
<?php include '../includes/footer.php'; ?>