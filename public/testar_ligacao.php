<?php
// 1. Inclui o arquivo de conexão
require_once '../backend/db.php';

try {
    echo "Conexão com o banco de dados estabelecida com sucesso!<br><br>";
    
    // 2. Preparar e executar a consulta para buscar usuário com ID 1
    $id = 1;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // 3. Obter o resultado
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Verificar se encontrou o usuário
    if ($user) {
        echo "Usuário encontrado:<br>";
        echo "<pre>";  // Formata melhor a saída
        print_r($user); // Mostra todos os campos do usuário
        echo "</pre>";
    } else {
        echo "Nenhum usuário encontrado com ID 1";
    }
    
} catch (PDOException $e) {
    // Em caso de erro na consulta
    echo "Erro na consulta: " . $e->getMessage();
}
?>
