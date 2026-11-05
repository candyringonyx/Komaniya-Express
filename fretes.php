
<?php
$conexao = new PDO(
    "mysql:host=localhost;dbname=komaniya-express;charset=utf8",
    "root",
    ""
);

// CADASTRAR FRETE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "INSERT INTO fretes
            (produto_id, origem_id, destino_id, transportadora_id, data_frete)
            VALUES (?, ?, ?, ?, ?)";

    $comando = $conexao->prepare($sql);

    $comando->execute([
        $_POST["produto_id"],
        $_POST["origem_id"],
        $_POST["destino_id"],
        $_POST["transportadora_id"],
        $_POST["data_frete"]
    ]);

    header("Location: fretes.php");
    exit;
}

// BUSCAR DADOS PARA OS CAMPOS DE SELEÇÃO
$produtos = $conexao->query("SELECT * FROM produtos");
$enderecos = $conexao->query("SELECT * FROM enderecos");
$transportadoras = $conexao->query("SELECT * FROM transportadoras");

// LISTAR FRETES
$sql = "SELECT
            fretes.*,
            produtos.nome AS produto,
            origem.nome AS origem,
            destino.nome AS destino,
            transportadoras.nome AS transportadora
        FROM fretes
        INNER JOIN produtos ON fretes.produto_id = produtos.id
        INNER JOIN enderecos AS origem ON fretes.origem_id = origem.id
        INNER JOIN enderecos AS destino ON fretes.destino_id = destino.id
        INNER JOIN transportadoras ON fretes.transportadora_id = transportadoras.id";

$fretes = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Fretes - Komaniya Express</title>
</head>

<body>

<div class="container mt-5">

    <h1 class="text-center" style="color: #7b2cbf;">
        Cadastro de Fretes
    </h1>

    <p class="text-center">
        Gerenciamento de fretes do sistema.
    </p>

    <form method="POST" action="">

        <div class="mb-3">
            <label class="form-label">Produto</label>
            <select name="produto_id" class="form-select" required>
                <option value="">Selecione um produto</option>

                <?php while ($produto = $produtos->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?php echo $produto['id']; ?>">
                        <?php echo htmlspecialchars($produto['nome']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Endereço de origem</label>
            <select name="origem_id" class="form-select" required>
                <option value="">Selecione a origem</option>

                <?php while ($endereco = $enderecos->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?php echo $endereco['id']; ?>">
                        <?php echo htmlspecialchars($endereco['nome']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Endereço de destino</label>
            <select name="destino_id" class="form-select" required>
                <option value="">Selecione o destino</option>

                <?php
                $enderecos->execute();
                while ($endereco = $enderecos->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <option value="<?php echo $endereco['id']; ?>">
                        <?php echo htmlspecialchars($endereco['nome']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Transportadora</label>
            <select name="transportadora_id" class="form-select" required>
                <option value="">Selecione uma transportadora</option>

                <?php while ($transportadora = $transportadoras->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?php echo $transportadora['id']; ?>">
                        <?php echo htmlspecialchars($transportadora['nome']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Data do frete</label>
            <input type="date" name="data_frete" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Cadastrar frete
        </button>

    </form>

    <h2 class="mt-5">Fretes cadastrados</h2>

    <table class="table table-bordered table-striped">

        <tr>
            <th>Produto</th>
            <th>Origem</th>
            <th>Destino</th>
            <th>Transportadora</th>
            <th>Data</th>
            <th>Status</th>
        </tr>

        <?php while ($frete = $fretes->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($frete["produto"]); ?></td>
            <td><?php echo htmlspecialchars($frete["origem"]); ?></td>
            <td><?php echo htmlspecialchars($frete["destino"]); ?></td>
            <td><?php echo htmlspecialchars($frete["transportadora"]); ?></td>
            <td><?php echo date("d/m/Y", strtotime($frete["data_frete"])); ?></td>
            <td><?php echo htmlspecialchars($frete["status"]); ?></td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>
