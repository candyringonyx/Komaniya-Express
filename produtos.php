
<?php
$conexao = new PDO(
    "mysql:host=localhost;dbname=komaniya-express;charset=utf8",
    "root",
    ""
);

// EXCLUIR PRODUTO
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];

    try {
        $sql = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
        $sql->execute([$id]);

        header("Location: produtos.php");
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == "23000") {
            echo "<script>
                alert('Não é possível excluir este produto porque ele está vinculado a um frete.');
                window.location.href = 'produtos.php';
            </script>";
            exit;
        } else {
            throw $e;
        }
    }
}

// BUSCAR PRODUTO PARA EDIÇÃO
$produtoEditar = null;

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $comando = $conexao->prepare(
        "SELECT * FROM produtos WHERE id = ?"
    );

    $comando->execute([$id]);

    $produtoEditar = $comando->fetch(PDO::FETCH_ASSOC);
}

// CADASTRAR OU ATUALIZAR PRODUTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["id"])) {
        $sql = "UPDATE produtos SET
                nome = ?,
                codigo = ?,
                descricao = ?,
                quantidade = ?,
                peso = ?
                WHERE id = ?";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            $_POST["nome"],
            $_POST["codigo"],
            $_POST["descricao"],
            $_POST["quantidade"],
            $_POST["peso"],
            $_POST["id"]
        ]);

    } else {
        $sql = "INSERT INTO produtos
                (nome, codigo, descricao, quantidade, peso)
                VALUES (?, ?, ?, ?, ?)";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            $_POST["nome"],
            $_POST["codigo"],
            $_POST["descricao"],
            $_POST["quantidade"],
            $_POST["peso"]
        ]);
    }

    header("Location: produtos.php");
    exit;
}

// LISTAR PRODUTOS
$sql = "SELECT * FROM produtos";
$produtos = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Produtos - Komaniya Express</title>
</head>

<body>

<?php include "menu.php"; ?>

<div class="container mt-5">

    <h1 class="text-center" style="color: #7b2cbf;">
        Cadastro de Produtos
    </h1>

    <p class="text-center">
        Gerenciamento de produtos do sistema.
    </p>

    <form method="POST" action="">

        <input type="hidden" name="id"
        value="<?php echo $produtoEditar['id'] ?? ''; ?>">

        <div class="mb-3">
            <label class="form-label">Nome do produto</label>
            <input type="text" name="nome" class="form-control"
            value="<?php echo $produtoEditar['nome'] ?? ''; ?>"
            placeholder="Digite o nome" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control"
            value="<?php echo $produtoEditar['codigo'] ?? ''; ?>"
            placeholder="Código do produto" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="descricao" class="form-control"
            value="<?php echo $produtoEditar['descricao'] ?? ''; ?>"
            placeholder="Descrição do produto">
        </div>

        <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" name="quantidade" class="form-control"
            value="<?php echo $produtoEditar['quantidade'] ?? ''; ?>"
            required>
        </div>

        <div class="mb-3">
            <label class="form-label">Peso (kg)</label>
            <input type="number" name="peso" step="0.01"
            class="form-control"
            value="<?php echo $produtoEditar['peso'] ?? ''; ?>"
            required>
        </div>

        <button type="submit" class="btn btn-primary">
            <?php echo $produtoEditar ? 'Salvar alterações' : 'Cadastrar produto'; ?>
        </button>

    </form>

    <h2 class="mt-5">Produtos cadastrados</h2>

    <table class="table table-bordered table-striped">

        <tr>
            <th>Nome</th>
            <th>Código</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Peso</th>
            <th>Ações</th>
        </tr>

        <?php while ($produto = $produtos->fetch(PDO::FETCH_ASSOC)) { ?>

        <tr>
            <td><?php echo htmlspecialchars($produto["nome"]); ?></td>
            <td><?php echo htmlspecialchars($produto["codigo"]); ?></td>
            <td><?php echo htmlspecialchars($produto["descricao"]); ?></td>
            <td><?php echo $produto["quantidade"]; ?></td>
            <td><?php echo $produto["peso"]; ?> kg</td>

            <td>
                <a href="produtos.php?editar=<?php echo $produto['id']; ?>"
                class="btn btn-warning btn-sm">
                    Editar
                </a>

                <a href="produtos.php?excluir=<?php echo $produto['id']; ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Deseja excluir este produto?')">
                    Excluir
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
