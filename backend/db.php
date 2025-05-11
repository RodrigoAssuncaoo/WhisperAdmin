<?php
$host = 'localhost';      // Servidor MySQL
$user = 'root';           // Nome de utilizador (normalmente 'root' no XAMPP)
$pass = '';               // Palavra-passe (normalmente vazio no XAMPP)
$db = 'lisbonwhisper';       // Nome da tua base de dados

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na ligação à base de dados: " . $conn->connect_error);
}
?>