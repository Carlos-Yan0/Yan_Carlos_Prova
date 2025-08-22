<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION["id_usuario"])) {
    echo "<script>alert('Acesso Negado!');window.location.href='index.php';</script>";
    exit();
}

if ($_SESSION['perfil'] == 4) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php'</script>";
    exit();
}

$sql = "SELECT  * FROM produto ORDER BY id_produto";
$stmt = $pdo->prepare($sql);

$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id_produto = $_GET["id"];

    $sql = "DELETE FROM produto WHERE id_produto = :id_produto";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id_produto", $id_produto, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        echo "<script>alert('Produto excluido com sucesso!');window.location.href='excluir_produto.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuario</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once "sidebar.php"; ?>
    <hr>
    <h2>Excluir Produtos</h2>
    <hr>
    <?php if(!empty($produtos)): ?>

        <table class="table table-hover">
        <thead class="table-secondary">
            <th>ID</th>
            <th>Produto</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Valor Unitario</th>
            <th>ações</th>
        </thead>
        <tbody>
                <?php foreach($produtos as $produto):?>
                    <tr>
                        <td><?=htmlspecialchars($produto["id_produto"])?></td>
                        <td><?=htmlspecialchars($produto["nome_prod"])?></td>
                        <td><?=htmlspecialchars($produto["descricao"])?></td>
                        <td><?=htmlspecialchars($produto["qtde"])?></td>
                        <td><?=htmlspecialchars($produto["valor_unit"])?></td>
                        <td>
                            <a href="excluir_produto.php?id=<?=htmlspecialchars($produto['id_produto'])?>" onclick="return confirm('Tem certeza que deseja excluir esse produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

        <?php else: ?>
                <p>Nenhum produto encontrado.</p>
        <?php endif; ?>

        <a href="principal.php">Voltar</a>
        <address>Yan Carlos de Oliveira - Desenvolvimento de Sistemas - Senai</address>

</body>
</html>