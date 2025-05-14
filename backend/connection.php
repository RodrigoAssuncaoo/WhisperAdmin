<?php

$dns = 'localhost';
$username = 'root'; //depois quando criar o user tenho de meter o nome do user
$passwordDatabase = ''; //depois quando criar o user tenho de meter a password do user
$database = 'private_info_app';

$connection = mysqli_connect($dns, $username, $passwordDatabase, $database);


if (!$connection) {
    throw new Exception('Erro de conexão: ' . mysqli_connect_error());
}
?>