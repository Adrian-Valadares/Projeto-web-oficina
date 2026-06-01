<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['cargo'] != 'admin'){
    die("Acesso negado");
}

include 'config.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare(
    "DELETE FROM ordens_servico WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

header("Location: painel.php");
exit();