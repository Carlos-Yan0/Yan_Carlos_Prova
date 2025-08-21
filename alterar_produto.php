<?php
session_start();
require_once "conexao.php";

if ($_SESSION['perfil'] == 4) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php'</script>";
    exit();
}

$busca = null;
$produto = null;

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
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$produto){
        echo "<script>alert('Produto não encontrado!');window.location.href='alterar_produto.php'</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Produto</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once "sidebar.php";?>
    <hr>
    <h2>Alterar Produto</h2>
    <hr>
    <form action="alterar_produto.php" method="POST">
        <label for="busca">Digite o ID ou o nome do produto</label>
        <input type="text" name="busca" class="form-control">

        <div id="sugestoes"></div>
        <button type="submit" class="btn btn-success">Buscar</button>
    </form>


    <?php if ($produto): ?>
        <!-- FORMULARIO PARA ALTERAR USUARIO -->
        <form action="processa_alteracao_produto.php" method="POST">
            <input type="hidden" name="id_produto" class="form-control" value="<?= htmlspecialchars($produto['id_produto']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome_prod" name="nome_prod" class="form-control" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" class="form-control" value="<?= htmlspecialchars($produto['descricao']) ?>" required>

            <label for="quantidade">Quantidade:</label>
            <input type="number" id="qtde" name="qtde" class="form-control" value="<?= htmlspecialchars($produto['qtde']) ?>" required min="0">

            <label for="valor_unitario">Valor Unitario:</label>
            <input type="number" id="valor_unit" name="valor_unit" class="form-control" value="<?= htmlspecialchars($produto['valor_unit']) ?>" required min="0" step="0.1">

            <button type="submit" class="btn btn-success">Alterar</button>
            <button type="reset" class="btn btn-success">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
    <address>Yan Carlos de Oliveira - Desenvolvimento de Sistemas - Senai</address>
    

</body>
</html>