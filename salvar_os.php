<?php


session_start();

if($_SESSION['cargo'] != 'admin'){
    die("Acesso negado");
}

include 'config.php';

$stmt = $conn->prepare(
"INSERT INTO ordens_servico
(data_servico, placa, descricao, valor)
VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
    "sssd",
    $_POST['data_servico'],
    $_POST['placa'],
    $_POST['descricao'],
    $_POST['valor']
);

$stmt->execute();

header("Location: painel.php");
exit();