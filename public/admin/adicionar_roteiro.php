<?php
require_once('../../backend/db.php');

$stmt = $pdo->query("SELECT id, nome FROM pontos ORDER BY nome ASC");
$pontos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST['nome'];
        $typeId = $_POST['id_tipo_roteiro'];
        $picPath = $_POST['picPath'];
        $selectedPoints = $_POST['pontos'];

        $stmt = $pdo->prepare("INSERT INTO roteiros (id_tipo_roteiro, nome, picPath) VALUES (?, ?, ?)");
        $stmt->execute([$typeId, $name, $picPath]);
        $routeId = $pdo->lastInsertId();

        $stmtPoint = $pdo->prepare("INSERT INTO roteiro_pontos (id_roteiro, id_ponto) VALUES (?, ?)");
        foreach ($selectedPoints as $pointId) {
            $stmtPoint->execute([$routeId, $pointId]);
        }

        header("Location: tables/roteirostable.php?sucesso=Route created successfully!");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Route</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
    <script>
        function addPointField() {
            const container = document.getElementById("pointsContainer");
            const select = document.querySelector(".pointSelect").cloneNode(true);
            container.appendChild(document.createElement("br"));
            container.appendChild(select);
        }
    </script>
</head>
<body class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title mb-4">Add New Route</h4>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Route Name</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Route Type ID</label>
                        <input type="number" class="form-control" name="id_tipo_roteiro" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Route Points</label>
                        <div id="pointsContainer">
                            <select name="pontos[]" class="form-select pointSelect">
                                <?php foreach ($pontos as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addPointField()">+ Add another point</button>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success">Create Route</button>
                    <a href="tables/roteirostable.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
