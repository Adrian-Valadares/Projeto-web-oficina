<?php

session_start();

if($_SESSION['cargo'] != 'admin'){
    die("Acesso negado");
}

include 'config.php';

$stmt = $conn->prepare(
"UPDATE ordens_servico
SET
data_servico = ?,
placa = ?,
descricao = ?,
valor = ?
WHERE id = ?"
);

$stmt->bind_param(
"sssdi",
$_POST['data_servico'],
$_POST['placa'],
$_POST['descricao'],
$_POST['valor'],
$_POST['id']
);

$stmt->execute();

header("Location: painel.php");
exit();