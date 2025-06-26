<?php
require_once '../../backend/db.php';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM roteiros WHERE id = ?");
$stmt->execute([$id]);
$roteiro = $stmt->fetch();

$stmtPontos = $pdo->query("SELECT id, nome FROM pontos ORDER BY nome ASC");
$todosPontos = $stmtPontos->fetchAll();

$stmtAssoc = $pdo->prepare("SELECT id_ponto FROM roteiro_pontos WHERE id_roteiro = ?");
$stmtAssoc->execute([$id]);
$pontosAssociados = array_column($stmtAssoc->fetchAll(), 'id_ponto');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $id_tipo = $_POST['id_tipo_roteiro'];
    $picPath = $_POST['picPath'];
    $novosPontos = $_POST['pontos'];

    $stmt = $pdo->prepare("UPDATE roteiros SET nome = ?, id_tipo_roteiro = ?, picPath = ? WHERE id = ?");
    $stmt->execute([$nome, $id_tipo, $picPath, $id]);

    $pdo->prepare("DELETE FROM roteiro_pontos WHERE id_roteiro = ?")->execute([$id]);

    $stmtInsert = $pdo->prepare("INSERT INTO roteiro_pontos (id_roteiro, id_ponto) VALUES (?, ?)");
    foreach ($novosPontos as $p) {
        $stmtInsert->execute([$id, $p]);
    }

    header("Location: tables/roteirostable.php?sucesso=Roteiro atualizado com sucesso!");
    exit;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Editar Roteiro</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Alterar Dados do Roteiro</h5>

                        <form method="POST" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($roteiro['nome']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ID Tipo Roteiro</label>
                                <input type="number" name="id_tipo_roteiro" class="form-control" value="<?= $roteiro['id_tipo_roteiro'] ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Imagem (picPath)</label>
                                <input type="text" name="picPath" class="form-control" value="<?= htmlspecialchars($roteiro['picPath']) ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Pontos Associados</label>
                                <div class="row">
                                    <?php foreach ($todosPontos as $p): ?>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="pontos[]" value="<?= $p['id'] ?>"
                                                    <?= in_array($p['id'], $pontosAssociados) ? 'checked' : '' ?>>
                                                <label class="form-check-label"><?= htmlspecialchars($p['nome']) ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                                <a href="tables/roteirostable.php" class="btn btn-secondary">Cancelar</a>
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