<?php
include_once '../../backend/connection.php';
include_once '../../backend/models/user.php';

mysqli_begin_transaction($connection);

//trigger exception in a "try" block
try {
    // Validar se tipo de pedido é o correto (POST)
    if ('POST' != $_SERVER['REQUEST_METHOD']) {
        throw new Exception('Método não permitido');
    }

    if (count($_POST) != 7) {
        throw new Exception('Número de parâmetros inválidos');
    }
    // Validar se o valor das variáveis é o correto
    if (!isset($_POST['isAdmin']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password']) || !isset($_POST['data_nascimento']) || !isset($_POST['telefone'])) {
        throw new Exception('Parâmetros inválidos');
    }

    $isAdmin = trim($_POST['isAdmin']);
    $nome = trim($_POST['name']);
    $dataNascimento = trim($_POST['data_nascimento']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    // Validar se o valor das variáveis é o correto
    if (strlen($nome) == 0 || strlen($email) == 0 || strlen($password) == 0 || strlen($confirmPassword) == 0 || strlen($dataNascimento) == 0 || strlen($telefone) == 0 || strlen($isAdmin) == 0) {
        throw new Exception('Dados inválidos!');
    }
    // Validar se a confirm_password = password
    if ($password != $confirmPassword) {
        throw new Exception('As passwords não coincidem!');
    }

    //validar se o email já existe na base de dados
    $sqlSelect = "SELECT * FROM utilizadores WHERE email = ?";

    if ($stmt = mysqli_prepare($connection, $sqlSelect)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        // "s" indica que o parâmetro é uma string
        mysqli_stmt_execute($stmt);
        //var_dump($stmt);
        $resultSelect = mysqli_stmt_store_result($stmt);
        //var_dump($resultSelect);
        //die;

        if (mysqli_stmt_num_rows($stmt) > 0) {
            throw new Exception('Impossível criar o utilizador');
        }
    }

    $sql = "INSERT INTO utilizadores(isAdmin, nome, data_nascimento, telefone, email, password ) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "isiiss", $isAdmin, $nome, $dataNascimento, $telefone, $email, $password);
        // "sss" indica que os três parâmetros são strings
        if (mysqli_stmt_execute($stmt)) {
            //echo "Registo inserido com sucesso!";
            //var_dump($stmt -> affected_rows);
            //também dava if(!mysqli_stmt_affected_rows($stmt)){
            if (!$stmt->affected_rows) {
                throw new Exception('Nenhum registo inserido!');
            }

            //Se quisermos saber o id do registo que foi inserido, podemos usar a função mysqli_insert_id($connection)
            //$idCriado = mysqli_insert_id($connection);
            //var_dump($idCriado);

        } else {
            throw new Exception('Erro ao inserir os dados: ' . mysqli_error($connection));
        }
    } else {
        throw new Exception('Erro ao preparar a consulta: ' . mysqli_error($connection));
    }
    mysqli_stmt_close($stmt);

    //var_dump($connection);

    mysqli_commit($connection);
    $result = [
        'success' => TRUE,
        'data' => 'success'
    ];

    echo json_encode($result);
}
//catch exception
catch (Exception $e) {
    mysqli_rollback($connection);
    //echo 'Message: ' . $e->getMessage();

    $result = [
        'success' => FALSE,
        'message' => $e->getMessage()
    ];
    echo json_encode($result);
} finally {
    mysqli_close($connection);
}
