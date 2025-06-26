<?php
require_once('../../backend/db.php');

$stmt = $pdo->query("SELECT id, nome FROM pontos ORDER BY nome ASC");
$pontos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nome = $_POST['nome'];
        $id_tipo = $_POST['id_tipo_roteiro'];
        $picPath = $_POST['picPath'];
        $pontosSelecionados = $_POST['pontos'];

        $stmt = $pdo->prepare("INSERT INTO roteiros (id_tipo_roteiro, nome, picPath) VALUES (?, ?, ?)");
        $stmt->execute([$id_tipo, $nome, $picPath]);
        $idRoteiro = $pdo->lastInsertId();

        $stmtPonto = $pdo->prepare("INSERT INTO roteiro_pontos (id_roteiro, id_ponto) VALUES (?, ?)");
        foreach ($pontosSelecionados as $idPonto) {
            $stmtPonto->execute([$idRoteiro, $idPonto]);
        }

        header("Location: tables/roteirostable.php?sucesso=Roteiro criado com sucesso!");
        exit;
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Roteiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
    <script>
        function adicionarCampoPonto() {
            const container = document.getElementById("pontosContainer");
            const select = document.querySelector(".pontoSelect").cloneNode(true);
            container.appendChild(document.createElement("br"));
            container.appendChild(select);
        }
    </script>
</head>
<body class="container mt-5">
    <div class="card shadow animate__animated animate__fadeInDown">
        <div class="card-body">
            <h4 class="card-title mb-4">Adicionar Novo Roteiro</h4>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nome do Roteiro</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ID Tipo Roteiro</label>
                        <input type="number" class="form-control" name="id_tipo_roteiro" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Imagem (picPath)</label>
                        <input type="text" class="form-control" name="picPath" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Pontos do Roteiro</label>
                        <div id="pontosContainer">
                            <select name="pontos[]" class="form-select pontoSelect">
                                <?php foreach ($pontos as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="adicionarCampoPonto()">+ Adicionar mais um ponto</button>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success">Criar Roteiro</button>
                    <a href="tables/roteirostable.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
