<?php
require_once '../../backend/db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

if (!isset($_GET['id'])) {
    die("ID not provided.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT r.*, tr.nome AS tipo_nome FROM roteiros r JOIN tipo_roteiros tr ON r.id_tipo_roteiro = tr.id WHERE r.id = ?");
$stmt->execute([$id]);
$roteiro = $stmt->fetch();

$stmtPontos = $pdo->prepare("SELECT p.nome, p.latitude, p.longitude FROM roteiro_pontos rp JOIN pontos p ON rp.id_ponto = p.id WHERE rp.id_roteiro = ?");
$stmtPontos->execute([$id]);
$pontos = $stmtPontos->fetchAll();
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Route Details</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= htmlspecialchars($roteiro['nome']) ?></h4>

                        <p><strong>Type:</strong> <?= htmlspecialchars($roteiro['tipo_nome']) ?></p>
                        <h5>Route Points:</h5>
                        <ul class="list-group">
                            <?php foreach ($pontos as $p): ?>
                                <li class="list-group-item"><?= htmlspecialchars($p['nome']) ?>
                                    <span class="badge bg-info"><?= $p['latitude'] ?>, <?= $p['longitude'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="tables/roteirostable.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>
