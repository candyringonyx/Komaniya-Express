<?php
$nomeSistema = "Komaniya Express";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Komaniya Express</title>

<style>


body {
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)),
        url("fundo.jpeg");
    background-size: 250px;
    background-position: top left;
    background-repeat: repeat;
    padding: 20px;
    min-height: 100vh;
}

h1 {
    color: #7b2cbf;
}

p {
    font-size: 18px;
}

.cartao {
    background-color: rgba(255, 255, 255, 0.92);
    padding: 30px;
    border-radius: 15px;
    max-width: 700px;
    margin: 40px auto;
    text-align: center;
}
</style>

</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Komaniya Express
        </a>
<div class="navbar-nav flex-row flex-wrap">
    <a class="nav-link me-3" href="produtos.php">Produtos</a>
    <a class="nav-link me-3" href="enderecos.php">Endereços</a>
    <a class="nav-link me-3" href="transportadoras.php">Transportadoras</a>
    <a class="nav-link me-3" href="fretes.php">Fretes</a>
    <a class="nav-link me-3" href="relatorio.php">Relatórios</a>
</div>
    </div>
</nav>
<div class="cartao">
    <h1>Komaniya Express</h1>
    <h2>Gerenciador Logístico</h2>
    <p>Bem-vindo ao sistema!</p>
<p> Gerencie seus produtos e fretes. </p>
</button>
</div>
</body>
</html>