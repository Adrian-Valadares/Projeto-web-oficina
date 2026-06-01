<?php

session_start();

if($_SESSION['cargo'] != 'admin'){
    die("Acesso negado");
}

include 'config.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare(
    "SELECT * FROM ordens_servico WHERE id = ?"
);

$stmt->bind_param("i",$id);

$stmt->execute();

$result = $stmt->get_result();

$os = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Ordem</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/editar_os.css">

</head>
<body>
    <header>

    <img src="img/logo-final.png" alt="Logo" class="logo">

    <nav class="nav">

        <a href="index.html">Site</a>

        <a href="painel.php">Ordens de Serviço</a>

        <?php if($_SESSION['cargo'] == 'admin'): ?>
            <a href="nova_os.php">Nova OS</a>
        <?php endif; ?>

        <a href="logout.php">Sair</a>

    </nav>

</header>

<?php include 'header.php'; ?>

<div class="os-container">

<div class="os-card">

<h1 class="os-title">
Editar Ordem de Serviço
</h1>

<form action="atualizar_os.php" method="POST">

<input
type="hidden"
name="id"
value="<?= $os['id'] ?>"
>

<label>Data</label>

<input
type="date"
name="data_servico"
value="<?= $os['data_servico'] ?>"
required
>

<br><br>

<label>Placa</label>

<input
type="text"
name="placa"
value="<?= $os['placa'] ?>"
required
>

<br><br>

<label>Descrição</label>

<textarea
name="descricao"
required><?= $os['descricao'] ?></textarea>

<br><br>

<label>Valor</label>

<input
type="number"
step="0.01"
name="valor"
value="<?= $os['valor'] ?>"
required
>

<br><br>

<button type="submit">
Salvar Alterações
</button>

</form>

</div>

</div>

</body>
</html>