<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION["id_usuario"])) {
    echo "<script>alert('Acesso Negado!');window.location.href='index.php';</script>";
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);

    //VERIFICA SE A BUSCA É UM NUMERO OU UM NOME

    if(is_numeric($busca)){
        $sql = "SELECT * FROM produto WHERE id_produto = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
    }else{
        $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":busca_nome", "%$busca%", PDO::PARAM_STR);
    }

}else{
    $sql = "SELECT  * FROM produto ORDER BY id_produto";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Produtos</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once "sidebar.php";?>
    <hr>
    <h2>Lista de Produtos</h2>
    <hr>
    <form action="buscar_produto.php" method="POST">
        <label for="busca">Digite o ID ou o nome do produto</label>
        <input type="text" name="busca" class="form-control">
        <button type="submit" class="btn btn-success">Buscar</button>
    </form>

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
                            <a href="alterar_produto.php?id=<?=htmlspecialchars($produto['id_produto'])?>">Alterar</a>

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