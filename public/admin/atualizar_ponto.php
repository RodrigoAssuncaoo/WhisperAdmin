<?php
require_once '../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $longitude = floatval(str_replace(',', '.', $_POST['longitude'] ?? 0));
    $latitude = floatval(str_replace(',', '.', $_POST['latitude'] ?? 0));
    $ponto_inicial = intval($_POST['ponto_inicial'] ?? 0);
    $ponto_final = intval($_POST['ponto_final'] ?? 0);

    if (
        $id <= 0 ||
        empty($nome) ||
        !is_numeric($longitude) || !is_numeric($latitude) ||
        !in_array($ponto_inicial, [0, 1]) ||
        !in_array($ponto_final, [0, 1])
    ) {
        header("Location: tables/pontostable.php?erro=Dados inválidos");
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE pontos SET nome = ?, longitude = ?, latitude = ?, ponto_inicial = ?, ponto_final = ? WHERE id = ?");
        $stmt->execute([$nome, $longitude, $latitude, $ponto_inicial, $ponto_final, $id]);
        header("Location: tables/pontostable.php?sucesso=Ponto atualizado com sucesso.");
        exit;
    } catch (PDOException $e) {
        header("Location: tables/pontostable.php?erro=" . urlencode($e->getMessage()));
        exit;
    }
}
