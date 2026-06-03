<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include 'config.php';

/* PAGINAÇÃO */
$porPagina = 30;

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if($pagina < 1){
    $pagina = 1;
}

$inicioPagina = ($pagina - 1) * $porPagina;

/* FILTRO */
$where = "";

if(!empty($_GET['data_inicio']) && !empty($_GET['data_fim'])){

    $dataInicio = $_GET['data_inicio'];
    $dataFim = $_GET['data_fim'];

    $where = " WHERE data_servico BETWEEN '$dataInicio' AND '$dataFim'";
}

/* TOTAL REGISTROS */
$totalRegistros = $conn
->query("SELECT COUNT(*) as total FROM ordens_servico $where")
->fetch_assoc()['total'];

$totalPaginas = ceil($totalRegistros / $porPagina);

/* CONSULTA */
$sql = "
SELECT *
FROM ordens_servico
$where
ORDER BY data_servico DESC
LIMIT $inicioPagina, $porPagina
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel da Oficina</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/painel.css">
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

<div class="painel-container">
<div class="painel-card">

<h1 class="painel-title">Painel da Oficina</h1>

<p class="usuario">
Usuário: <?= $_SESSION['usuario'] ?>
</p>

<?php if($_SESSION['cargo'] == 'admin'): ?>
<a href="nova_os.php" class="btn-nova">+ Nova Ordem de Serviço</a>
<?php endif; ?>

<form method="GET" class="filtro">

<label>De:</label>
<input type="date" name="data_inicio" value="<?= $_GET['data_inicio'] ?? '' ?>">

<label>Até:</label>
<input type="date" name="data_fim" value="<?= $_GET['data_fim'] ?? '' ?>">

<button type="submit">Filtrar</button>
<?php 
$temFiltro = !empty($_GET['data_inicio']) && !empty($_GET['data_fim']);
?>

<?php if($temFiltro): ?>

<a
    href="exportar_pdf.php?data_inicio=<?= $_GET['data_inicio'] ?>&data_fim=<?= $_GET['data_fim'] ?>"
    class="btn-nova"
>
📄 Exportar PDF
</a>

<?php else: ?>

<a
    href="#"
    onclick="alert('Selecione as datas antes de exportar!'); return false;"
    class="btn-nova blocked"
    title="Você precisa selecionar as datas antes de exportar o PDF"
>
📄 Exportar PDF
</a>

<?php endif; ?>

</form>

<table class="tabela">

<thead>
<tr>
<th>Data</th>
<th>Placa</th>
<th>Descrição</th>
<th>Valor</th>

<?php if($_SESSION['cargo'] == 'admin'): ?>
<th>Ações</th>
<?php endif; ?>

</tr>
</thead>

<tbody>

<?php
$total = 0;

while($os = $result->fetch_assoc()):
$total += $os['valor'];
?>

<tr>

<td><?= date('d/m/Y', strtotime($os['data_servico'])) ?></td>

<td><?= htmlspecialchars($os['placa']) ?></td>

<!-- DESCRIÇÃO CORRIGIDA -->
<td class="descricao">
<?= htmlspecialchars($os['descricao']) ?>
</td>

<td class="valor">
R$ <?= number_format($os['valor'],2,',','.') ?>
</td>

<?php if($_SESSION['cargo'] == 'admin'): ?>
<td>

<a href="editar_os.php?id=<?= $os['id'] ?>" class="btn-editar">✏️ Editar</a>

<a href="excluir_os.php?id=<?= $os['id'] ?>"
   class="btn-excluir"
   onclick="return confirm('Deseja excluir esta ordem de serviço?')">
🗑️ Excluir
</a>

</td>
<?php endif; ?>

</tr>

<?php endwhile; ?>

</tbody>
</table>

<?php if(!empty($_GET['data_inicio']) && !empty($_GET['data_fim'])): ?>

<div class="total">

Período:
<?= date('d/m/Y', strtotime($_GET['data_inicio'])) ?>
até
<?= date('d/m/Y', strtotime($_GET['data_fim'])) ?>

<br><br>

Total faturado:
R$ <?= number_format($total,2,',','.') ?>

</div>

<?php endif; ?>

<!-- PAGINAÇÃO -->
<div class="paginacao">

<?php if($pagina > 1): ?>
<a href="?pagina=<?= $pagina - 1 ?>">◀</a>
<?php endif; ?>

<?php for($i = 1; $i <= $totalPaginas; $i++): ?>
<a href="?pagina=<?= $i ?>" class="<?= $i == $pagina ? 'ativo' : '' ?>">
<?= $i ?>
</a>
<?php endfor; ?>

<?php if($pagina < $totalPaginas): ?>
<a href="?pagina=<?= $pagina + 1 ?>">▶</a>
<?php endif; ?>

</div>

</div>
</div>

</body>
</html>