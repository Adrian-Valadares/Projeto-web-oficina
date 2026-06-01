<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
<body>
    <header>

    <img src="img/logo-final.png" alt="Logo" class="logo">

    <nav class="nav">

        <a href="index.html">Site</a>

    </nav>

</header>

<h2>Login</h2>
<?php if(isset($_GET['erro'])): ?>
    <div class="erro">
        Usuário ou senha incorretos
    </div>
<?php endif; ?>

<form action="auth.php" method="POST">

    <input
        type="text"
        name="usuario"
        placeholder="Usuário"
        required
    >

    <input
        type="password"
        name="senha"
        placeholder="Senha"
        required
    >

    <button type="submit">
        Entrar
    </button>

</form>



</body>
</html>