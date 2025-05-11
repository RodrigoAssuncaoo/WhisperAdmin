<?php
// Este ficheiro serve para testar se a base de dados está a funcionar corretamente
// e se a ligação à base de dados está a ser feita corretamente.

include '../backend/db.php'; 

try {
    // Exibe uma mensagem indicando que a ligação à base de dados foi bem-sucedida.
    echo ('Ligação à base de dados feita com sucesso!<br>');

    // Define uma query SQL para selecionar todos os campos da tabela 'clientes' onde o ID é igual a 1.
    $sql = "SELECT * FROM clientes WHERE id = 1";

    // Executa a query SQL utilizando o objeto de ligação à base de dados ($conn).
    $result = $conn->query($sql);

    // Verifica se a query retornou algum resultado.
    if ($result->num_rows > 0) {
        // Itera sobre os resultados retornados pela query.
        while ($row = $result->fetch_assoc()) {
            // Exibe o ID e o nome do cliente encontrado.
            echo "ID: " . $row["id"] . " - Nome: " . $row["nome"] . "<br>";
        }
    } else {
        // Caso nenhum cliente seja encontrado, exibe uma mensagem informativa.
        echo "Nenhum cliente encontrado.";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>