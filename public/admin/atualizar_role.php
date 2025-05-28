<?php
require_once '../../backend/connection.php'; // Caminho correto para a tua ligação

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $role = intval($_POST['role']);

    if (in_array($role, [1, 2, 3])) {
        $stmt = $connection->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("ii", $role, $id);

        if ($stmt->execute()) {
            header("Location: usersTables.php?msg=sucesso");
            exit();
        } else {
            echo "Erro ao atualizar o role: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Role inválido.";
    }
} else {
    echo "Requisição inválida.";
}

$connection->close();
