<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['cargo'] != 'admin'){
    die("Acesso negado");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nova Ordem de Serviço</title>

<link rel="stylesheet" href="css/style.css">

<style>
input[type="date"]{
    background:#1f242d;
    color:#fff;
    padding:10px;
    border:1px solid rgba(255,255,255,0.2);
    border-radius:8px;
}

/* Chrome / Edge / Safari */
input[type="date"]::-webkit-calendar-picker-indicator{
    filter: invert(1); /* 🔥 deixa o ícone branco */
    cursor:pointer;
}
.os-container{
    max-width:900px;
    margin:140px auto 50px;
    padding:20px;
}

.os-card{
    background:#1f242d;
    border-radius:16px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,.35);
}

.os-title{
    color:#fcd434;
    font-size:2rem;
    margin-bottom:25px;
    text-align:center;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    color:white;
    margin-bottom:8px;
    font-size:1rem;
}

.form-group input,
.form-group textarea{
    width:100%;
    padding:14px;
    border-radius:10px;
    border:1px solid rgba(255,255,255,.08);
    background:#111;
    color:white;
    font-size:1rem;
}

.form-group textarea{
    min-height:140px;
    resize:vertical;
}

.btn-salvar{
    width:100%;
    background:#fcd434;
    color:#111;
    padding:15px;
    border-radius:10px;
    font-size:1rem;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

.btn-salvar:hover{
    transform:translateY(-2px);
}

.btn-voltar{
    display:inline-block;
    margin-top:20px;
    color:#fcd434;
    font-weight:bold;
}

@media(max-width:768px){

    .os-card{
        padding:20px;
    }

    .os-title{
        font-size:1.6rem;
    }

}

</style>

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


<div class="os-container">

    <div class="os-card">

        <h1 class="os-title">
            Nova Ordem de Serviço
        </h1>

        <form action="salvar_os.php" method="POST">

            <div class="form-group">
                <label>Data do Serviço</label>
                <input
                    type="date"
                    name="data_servico"
                    value="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Placa do Veículo</label>
                <input
                    type="text"
                    name="placa"
                    placeholder="Ex: ABC1D23"
                    required
                >
            </div>

            <div class="form-group">
                <label>Descrição do Serviço</label>
                <textarea
                    name="descricao"
                    placeholder="Descreva o serviço realizado..."
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <label>Valor (R$)</label>
                <input
                    type="number"
                    step="0.01"
                    name="valor"
                    placeholder="0,00"
                    required
                >
            </div>

            <button type="submit" class="btn-salvar">
                Salvar Ordem de Serviço
            </button>

        </form>

        <a href="painel.php" class="btn-voltar">
            ← Voltar ao Painel
        </a>

    </div>

</div>

</body>
</html>