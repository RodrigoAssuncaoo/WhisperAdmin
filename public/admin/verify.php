<?php
session_start();

// Ativar exceções para MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Incluir ficheiro de ligação à base de dados
    require_once '.php'; // Certifica-te que este ficheiro define a variável $conn

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obter dados do formulário
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Validar campos
        if (empty($email) || empty($password)) {
            throw new Exception("Por favor, preencha todos os campos.");
        }

        // Preparar e executar a query
        $stmt = $conn->prepare("SELECT id, nome, email, password, isAdmin FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Obter resultados
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // ⚠️ Comparação sem password_hash (apenas para testes)
            if ($password === $user['password']) {
            // Para produção, usar:
            // if (password_verify($password, $user['password'])) {

                // Guardar info do utilizador na sessão
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["nome"];
                $_SESSION["is_admin"] = $user["isAdmin"];

                // Redirecionar
                if ($user["isAdmin"]) {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit();
            } else {
                throw new Exception("Palavra-passe incorreta.");
            }
        } else {
            throw new Exception("Utilizador não encontrado.");
        }
    } else {
        throw new Exception("Pedido inválido.");
    }

} catch (Exception $e) {
    // Mostrar mensagem de erro (para testes — remover em produção)
    echo "Erro: " . $e->getMessage();
    // Em produção: log do erro e redirecionamento amigável
    // error_log($e->getMessage());
    // header("Location: erro.php");
}
