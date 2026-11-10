
<?php
$conexao = new PDO(
    "mysql:host=localhost;dbname=komaniya-express;charset=utf8",
    "root",
    ""
);

// EXCLUIR ENDEREÇO
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];

    $comando = $conexao->prepare(
        "DELETE FROM enderecos WHERE id = ?"
    );

    $comando->execute([$id]);

    header("Location: enderecos.php");
    exit;
}

// BUSCAR ENDEREÇO PARA EDIÇÃO
$enderecoEditar = null;

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $comando = $conexao->prepare(
        "SELECT * FROM enderecos WHERE id = ?"
    );

    $comando->execute([$id]);

    $enderecoEditar = $comando->fetch(PDO::FETCH_ASSOC);
}

// CADASTRAR OU ATUALIZAR ENDEREÇO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["id"])) {

        $sql = "UPDATE enderecos SET
                nome = ?,
                rua = ?,
                numero = ?,
                cidade = ?,
                estado = ?,
                cep = ?,
                complemento = ?
                WHERE id = ?";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            $_POST["nome"],
            $_POST["rua"],
            $_POST["numero"],
            $_POST["cidade"],
            $_POST["estado"],
            $_POST["cep"],
            $_POST["complemento"],
            $_POST["id"]
        ]);

    } else {

        $sql = "INSERT INTO enderecos
                (nome, rua, numero, cidade, estado, cep, complemento)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            $_POST["nome"],
            $_POST["rua"],
            $_POST["numero"],
            $_POST["cidade"],
            $_POST["estado"],
            $_POST["cep"],
            $_POST["complemento"]
        ]);
    }

    header("Location: enderecos.php");
    exit;
}

// LISTAR ENDEREÇOS
$enderecos = $conexao->query("SELECT * FROM enderecos");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Endereços - Komaniya Express</title>
</head>

<body>

<?php include "menu.php"; ?>

<div class="container mt-5">

    <h1 class="text-center" style="color: #7b2cbf;">
        Cadastro de Endereços
    </h1>

    <p class="text-center">
        Gerenciamento de endereços de origem e destino.
    </p>

    <form method="POST" action="">

        <input type="hidden" name="id"
        value="<?php echo $enderecoEditar['id'] ?? ''; ?>">

        <div class="mb-3">
            <label class="form-label">Identificação do endereço</label>

            <input type="text" name="nome" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['nome'] ?? ''); ?>"
            placeholder="Ex.: Depósito Central" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Rua</label>

            <input type="text" name="rua" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['rua'] ?? ''); ?>"
            placeholder="Ex.: Avenida Brasil" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Número</label>

            <input type="text" name="numero" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['numero'] ?? ''); ?>"
            placeholder="Ex.: 150" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cidade</label>

            <input type="text" name="cidade" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['cidade'] ?? ''); ?>"
            placeholder="Ex.: Cascavel" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>

            <input type="text" name="estado" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['estado'] ?? ''); ?>"
            placeholder="Ex.: Paraná" required>
        </div>

        <div class="mb-3">
            <label class="form-label">CEP</label>

            <input type="text" name="cep" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['cep'] ?? ''); ?>"
            placeholder="Ex.: 85810-000">
        </div>

        <div class="mb-3">
            <label class="form-label">Complemento</label>

            <input type="text" name="complemento" class="form-control"
            value="<?php echo htmlspecialchars($enderecoEditar['complemento'] ?? ''); ?>"
            placeholder="Ex.: Sala 02, bloco A">
        </div>

        <button type="submit" class="btn btn-primary">
            <?php echo $enderecoEditar ? 'Salvar alterações' : 'Cadastrar endereço'; ?>
        </button>

    </form>

    <h2 class="mt-5">Endereços cadastrados</h2>

    <table class="table table-bordered table-striped">

        <tr>
            <th>Identificação</th>
            <th>Rua</th>
            <th>Número</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>CEP</th>
            <th>Complemento</th>
            <th>Ações</th>
        </tr>

        <?php while ($endereco = $enderecos->fetch(PDO::FETCH_ASSOC)) { ?>

        <tr>
            <td><?php echo htmlspecialchars($endereco["nome"]); ?></td>
            <td><?php echo htmlspecialchars($endereco["rua"]); ?></td>
            <td><?php echo htmlspecialchars($endereco["numero"]); ?></td>
            <td><?php echo htmlspecialchars($endereco["cidade"]); ?></td>
            <td><?php echo htmlspecialchars($endereco["estado"]); ?></td>
            <td><?php echo htmlspecialchars($endereco["cep"]); ?></td>
            <td><?php echo htmlspecialchars($endereco["complemento"]); ?></td>

            <td>
                <a href="enderecos.php?editar=<?php echo $endereco['id']; ?>"
                class="btn btn-warning btn-sm">Editar</a>

                <a href="enderecos.php?excluir=<?php echo $endereco['id']; ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Deseja excluir este endereço?')">
                Excluir</a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
