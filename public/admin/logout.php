<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
// destroy the session
session_destroy();
header('Location: /index.php');
?>
