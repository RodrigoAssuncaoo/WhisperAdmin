<?php
header("Content-Type: application/json");
require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Validação do token JWT
    verificarToken($connection);

    // Total de roteiros
    $stmt = mysqli_prepare($connection, "SELECT COUNT(*) AS total_roteiros FROM roteiros");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $totalRoteiros = $row['total_roteiros'] ?? 0;

    echo json_encode([
        'success' => true,
        'message' => 'Dashboard data retrieved successfully',
        'data' => [
            'total_roteiros' => $totalRoteiros
        ]
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
