<?php
ob_start(); // <- ADICIONADO AQUI
require_once '../../backend/db.php';

$id = $_GET['id'];

try {
    // Incluímos após o ob_start
    include 'includes/header.php'; 
    include 'includes/sidebar.php'; 

    // Obter dados do roteiro
    $stmt = $pdo->prepare("SELECT * FROM roteiros WHERE id = ?");
    $stmt->execute([$id]);
    $roteiro = $stmt->fetch();

    $stmtPontos = $pdo->query("SELECT id, nome FROM pontos ORDER BY nome ASC");
    $allPoints = $stmtPontos->fetchAll();

    $stmtAssoc = $pdo->prepare("SELECT id_ponto FROM roteiro_pontos WHERE id_roteiro = ?");
    $stmtAssoc->execute([$id]);
    $associatedPoints = array_column($stmtAssoc->fetchAll(), 'id_ponto');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST['nome'];
        $typeId = $_POST['id_tipo_roteiro'];
        $newPoints = $_POST['pontos'];

        $stmt = $pdo->prepare("UPDATE roteiros SET nome = ?, id_tipo_roteiro = ? WHERE id = ?");
        $stmt->execute([$name, $typeId, $id]);

        $pdo->prepare("DELETE FROM roteiro_pontos WHERE id_roteiro = ?")->execute([$id]);

        $stmtInsert = $pdo->prepare("INSERT INTO roteiro_pontos (id_roteiro, id_ponto) VALUES (?, ?)");
        foreach ($newPoints as $p) {
            $stmtInsert->execute([$id, $p]);
        }

        header("Location: tables/roteirostable.php?sucesso=Route updated successfully!");
        exit;
    }
} catch (PDOException $e) {
    header("Location: tables/roteirostable.php?erro=" . urlencode($e->getMessage()));
    exit;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Route</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Update Route Information</h5>

                        <form method="POST" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($roteiro['nome']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Route Type ID</label>
                                <input type="number" name="id_tipo_roteiro" class="form-control" value="<?= $roteiro['id_tipo_roteiro'] ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Associated Points</label>
                                <div class="row">
                                    <?php foreach ($allPoints as $p): ?>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="pontos[]" value="<?= $p['id'] ?>"
                                                    <?= in_array($p['id'], $associatedPoints) ? 'checked' : '' ?>>
                                                <label class="form-check-label"><?= htmlspecialchars($p['nome']) ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="tables/roteirostable.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>
<?php ob_end_flush(); // <- ADICIONADO AQUI ?>
