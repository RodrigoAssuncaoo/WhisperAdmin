<?php
header("Content-Type: application/json");

require_once '../../../vendor/autoload.php';
include_once '../../../backend/connection.php';
include_once '../../../backend/auth.php';

try {
    // Verifica se o método é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        exit;
    }

    // Verifica a conexão à base de dados
    if (!$connection) {
        throw new Exception("Erro na conexão com o banco de dados.");
    }

    // Obtém o user_id a partir do token (autenticação)
    $user_id = verificarToken($connection); // assume que a função retorna o ID do utilizador autenticado

    // Obtém os dados via form-data (POST)
    $idRoteiroCompras = $_POST['idRoteiroCompras'] ?? null;
    $idGrupoVisitas   = $_POST['idGrupoVisitas'] ?? null;
    $avaliacaoGuia    = $_POST['avaliacaoGuia'] ?? null;
    $avaliacaoViagem  = $_POST['avaliacaoViagem'] ?? null;
    $comentario       = $_POST['comentario'] ?? null;

    // Verifica se todos os campos obrigatórios foram enviados
    if (
        is_null($idRoteiroCompras) || 
        is_null($idGrupoVisitas)   || 
        is_null($avaliacaoGuia)    || 
        is_null($avaliacaoViagem)  || 
        is_null($comentario)
    ) {
        throw new Exception("Dados inválidos ou incompletos.");
    }

    // Validação: As avaliações devem ser números entre 1 e 5
    if (!is_numeric($avaliacaoGuia) || $avaliacaoGuia < 1 || $avaliacaoGuia > 5) {
        throw new Exception("A avaliação do guia deve ser um número entre 1 e 5.");
    }

    if (!is_numeric($avaliacaoViagem) || $avaliacaoViagem < 1 || $avaliacaoViagem > 5) {
        throw new Exception("A avaliação da viagem deve ser um número entre 1 e 5.");
    }

    // Prepara a query SQL
    $sql = "INSERT INTO avaliacoes (
                idRoteiroCompras, 
                idGrupoVisitas, 
                user_id, 
                avaliacaoGuia, 
                avaliacaoViagem, 
                comentario
            ) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $sql);

    // Faz o bind dos parâmetros
    mysqli_stmt_bind_param($stmt, "iiiiss", 
        $idRoteiroCompras, 
        $idGrupoVisitas, 
        $user_id, 
        $avaliacaoGuia, 
        $avaliacaoViagem, 
        $comentario
    );

    // Executa a query
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "mensagem" => "Avaliação criada com sucesso",
            "id" => mysqli_insert_id($connection)
        ]);
    } else {
        throw new Exception("Erro ao criar a avaliação.");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
