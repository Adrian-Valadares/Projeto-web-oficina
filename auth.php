<?php

session_start();
include 'config.php';

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    $user = $result->fetch_assoc();

    if(password_verify($senha, $user['senha'])){

        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['cargo'] = $user['cargo'];

        header("Location: painel.php");
        exit();
    }
}

// ERRO
header("Location: login.php?erro=1");
exit();

?>