<?php
require_once '../../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $longitude = floatval($_POST['longitude'] ?? 0);
    $latitude = floatval($_POST['latitude'] ?? 0);
    $ponto_inicial = $_POST['ponto_inicial'] ?? 'nao';
    $ponto_final = $_POST['ponto_final'] ?? 'nao';

    // Converter 'sim' para 1 e 'nao' para 0
    $ponto_inicial = ($ponto_inicial === 'sim') ? 1 : 0;
    $ponto_final = ($ponto_final === 'sim') ? 1 : 0;

    // Depuração: Mostrar os dados
    var_dump($nome, $longitude, $latitude, $ponto_inicial, $ponto_final);  // Mostra as variáveis no navegador

    // Validação simples
    if (
        empty($nome) ||
        !is_numeric($longitude) || !is_numeric($latitude) ||
        !in_array($ponto_inicial, [0, 1]) ||
        !in_array($ponto_final, [0, 1])
    ) {
        echo 'Erro de validação'; // Mostrar erro diretamente, sem redirecionamento
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO pontos (nome, longitude, latitude, ponto_inicial, ponto_final) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $longitude, $latitude, $ponto_inicial, $ponto_final]);
        
        // Remover o header temporariamente para depuração
        header("Location: tables/pontostable.php?sucesso=Ponto adicionado com sucesso.");
    exit;
            // header("Location: tables/pontostable.php?sucesso=Ponto adicionado com sucesso.");
    } catch (PDOException $e) {
    header("Location: tables/pontostable.php?erro=" . urlencode($e->getMessage()));
    }
}
?>
