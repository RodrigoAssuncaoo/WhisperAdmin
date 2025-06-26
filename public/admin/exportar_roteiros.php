<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;

require_once '../../backend/db.php';
session_start();

// Verificação de admin
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 1) {
    die("Acesso negado.");
}

// Obter os roteiros com tipo (via JOIN)
$sql = "
    SELECT r.id, r.nome AS nome_roteiro, t.nome AS tipo, t.duracao, t.preco
    FROM roteiros r
    LEFT JOIN tipos_roteiro t ON r.id_tipo_roteiro = t.id
    ORDER BY r.id ASC
";
$stmt = $pdo->query($sql);
$roteiros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Criar HTML
$html = '
<h1 style="text-align: center;">Lista de Roteiros - Whisper</h1>
<table style="width:100%; border-collapse: collapse;" border="1" cellpadding="5">
    <thead style="background-color:#f2f2f2;">
        <tr>
            <th>ID</th>
            <th>Nome do Roteiro</th>
            <th>Tipo</th>
            <th>Duração</th>
            <th>Preço (€)</th>
        </tr>
    </thead>
    <tbody>';

foreach ($roteiros as $r) {
    $html .= '<tr>
        <td>' . $r['id'] . '</td>
        <td>' . htmlspecialchars($r['nome_roteiro']) . '</td>
        <td>' . htmlspecialchars($r['tipo']) . '</td>
        <td>' . htmlspecialchars($r['duracao']) . '</td>
        <td>' . number_format($r['preco'], 2, ',', ' ') . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Gerar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("roteiros_whisper.pdf", array("Attachment" => false));
