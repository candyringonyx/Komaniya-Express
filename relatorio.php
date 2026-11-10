
<?php
$conexao = new PDO(
    "mysql:host=localhost;dbname=komaniya-express;charset=utf8",
    "root",
    ""
);

$fretes = [];

if (isset($_GET["data"])) {

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
            INNER JOIN transportadoras ON fretes.transportadora_id = transportadoras.id
            WHERE fretes.data_frete = ?";

    $comando = $conexao->prepare($sql);
    $comando->execute([$_GET["data"]]);

    $fretes = $comando->fetchAll(PDO::FETCH_ASSOC);
}
// BAIXAR RELATÓRIO
if (isset($_GET["baixar"]) && isset($_GET["data"])) {

    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=relatorio_fretes.csv");

    $arquivo = fopen("php://output", "w");

    // Compatibilidade com Excel
    fwrite($arquivo, "\xEF\xBB\xBF");

    fputcsv($arquivo, [
        "Produto",
        "Origem",
        "Destino",
        "Transportadora",
        "Data",
        "Status"
    ], ";");

    foreach ($fretes as $frete) {
        fputcsv($arquivo, [
            $frete["produto"],
            $frete["origem"],
            $frete["destino"],
            $frete["transportadora"],
            date("d/m/Y", strtotime($frete["data_frete"])),
            $frete["status"]
        ], ";");
    }

    fclose($arquivo);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Relatórios - Komaniya Express</title>
</head>

<body>

<?php include "menu.php"; ?>

<div class="container mt-5">

    <h1 class="text-center" style="color: #7b2cbf;">
        Relatório de Fretes
    </h1>

    <p class="text-center">
        Consulte os fretes realizados em uma data específica.
    </p>

    <form method="GET" action="">

        <div class="mb-3">
            <label class="form-label">Selecione a data</label>
            <input type="date" name="data" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Consultar fretes
        </button>

    </form>

    <?php if (isset($_GET["data"])) { ?>

        <h2 class="mt-5">Resultado da consulta</h2>

        <?php if (count($fretes) > 0) { ?>

            <table class="table table-bordered table-striped">

                <tr>
                    <th>Produto</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Transportadora</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($fretes as $frete) { ?>

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

            <a href="relatorio.php?data=<?php echo htmlspecialchars($_GET['data']); ?>&baixar=1"
               class="btn btn-success">
                Baixar relatório
            </a>

        <?php } else { ?>

            <p class="alert alert-warning mt-3">
                Nenhum frete encontrado para essa data.
            </p>

        <?php } ?>

    <?php } ?>

</div>

</body>
</html>
