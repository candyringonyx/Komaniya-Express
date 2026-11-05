
<?php
$conexao = new PDO(
    "mysql:host=localhost;dbname=komaniya-express;charset=utf8",
    "root",
    ""
);

// EXCLUIR TRANSPORTADORA
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];

    $comando = $conexao->prepare(
        "DELETE FROM transportadoras WHERE id = ?"
    );

    $comando->execute([$id]);

    header("Location: transportadoras.php");
    exit;
}

// BUSCAR TRANSPORTADORA PARA EDIÇÃO
$transportadoraEditar = null;

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $comando = $conexao->prepare(
        "SELECT * FROM transportadoras WHERE id = ?"
    );

    $comando->execute([$id]);

    $transportadoraEditar = $comando->fetch(PDO::FETCH_ASSOC);
}

// CADASTRAR OU ATUALIZAR TRANSPORTADORA
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["id"])) {
        $sql = "UPDATE transportadoras SET
                nome = ?,
                cnpj = ?,
                telefone = ?,
                email = ?,
                responsavel = ?
                WHERE id = ?";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            $_POST["nome"],
            $_POST["cnpj"],
            $_POST["telefone"],
            $_POST["email"],
            $_POST["responsavel"],
            $_POST["id"]
        ]);

    } else {
        $sql = "INSERT INTO transportadoras
                (nome, cnpj, telefone, email, responsavel)
                VALUES (?, ?, ?, ?, ?)";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            $_POST["nome"],
            $_POST["cnpj"],
            $_POST["telefone"],
            $_POST["email"],
            $_POST["responsavel"]
        ]);
    }

    header("Location: transportadoras.php");
    exit;
}

// LISTAR TRANSPORTADORAS
$transportadoras = $conexao->query(
    "SELECT * FROM transportadoras"
);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Transportadoras - Komaniya Express</title>
</head>

<body>
<div class="container mt-5">

    <h1 class="text-center" style="color: #7b2cbf;">
        Cadastro de Transportadoras
    </h1>

    <p class="text-center">
        Gerenciamento de empresas transportadoras.
    </p>

    <form method="POST" action="">

        <input type="hidden" name="id"
        value="<?php echo $transportadoraEditar['id'] ?? ''; ?>">

        <div class="mb-3">
            <label class="form-label">Nome da transportadora</label>
            <input type="text" name="nome" class="form-control"
            value="<?php echo htmlspecialchars($transportadoraEditar['nome'] ?? ''); ?>"
            placeholder="Nome da empresa" required>
        </div>

        <div class="mb-3">
            <label class="form-label">CNPJ</label>
            <input type="text" name="cnpj" class="form-control"
            value="<?php echo htmlspecialchars($transportadoraEditar['cnpj'] ?? ''); ?>"
            placeholder="CNPJ">
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control"
            value="<?php echo htmlspecialchars($transportadoraEditar['telefone'] ?? ''); ?>"
            placeholder="Telefone">
        </div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control"
            value="<?php echo htmlspecialchars($transportadoraEditar['email'] ?? ''); ?>"
            placeholder="E-mail">
        </div>

        <div class="mb-3">
            <label class="form-label">Responsável</label>
            <input type="text" name="responsavel" class="form-control"
            value="<?php echo htmlspecialchars($transportadoraEditar['responsavel'] ?? ''); ?>"
            placeholder="Nome do responsável">
        </div>

        <button type="submit" class="btn btn-primary">
            <?php echo $transportadoraEditar ? 'Salvar alterações' : 'Cadastrar transportadora'; ?>
        </button>

    </form>

    <h2 class="mt-5">Transportadoras cadastradas</h2>

    <table class="table table-bordered table-striped">
        <tr>
            <th>Nome</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Responsável</th>
            <th>Ações</th>
        </tr>

        <?php while ($transportadora = $transportadoras->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($transportadora["nome"]); ?></td>
            <td><?php echo htmlspecialchars($transportadora["cnpj"]); ?></td>
            <td><?php echo htmlspecialchars($transportadora["telefone"]); ?></td>
            <td><?php echo htmlspecialchars($transportadora["email"]); ?></td>
            <td><?php echo htmlspecialchars($transportadora["responsavel"]); ?></td>

            <td>
                <a href="transportadoras.php?editar=<?php echo $transportadora['id']; ?>"
                class="btn btn-warning btn-sm">Editar</a>

                <a href="transportadoras.php?excluir=<?php echo $transportadora['id']; ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Deseja excluir esta transportadora?')">
                Excluir</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>
</body>
</html>
