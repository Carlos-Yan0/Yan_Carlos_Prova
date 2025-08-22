<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION["id_usuario"])) {
    echo "<script>alert('Acesso Negado!');window.location.href='index.php';</script>";
    exit();
}

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php'</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nome_prod = $_POST["nome_prod"];
    $descricao = $_POST["descricao"];
    $qtde = $_POST["qtde"];

    if(!is_numeric($_POST["valor_unit"])){
        "echo <script>alert('Erro, o campo valor unitario deve conter apenas números');window.location.href='cadastro_produto.php'</script>";
        exit();
    }else{
        $valor_unit = $_POST["valor_unit"];
    }


    $sql = "INSERT INTO produto(nome_prod, descricao, qtde, valor_unit)VALUES(:nome_prod, :descricao, :qtde, :valor_unit)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome_prod", $nome_prod, PDO::PARAM_STR);
    $stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR);
    $stmt->bindParam(":qtde", $qtde, PDO::PARAM_INT);
    $stmt->bindParam(":valor_unit", $valor_unit, PDO::PARAM_STR);

    if($stmt->execute()){
        echo "<script>alert('Produto cadastrado com sucesso!');window.location.href='cadastro_produto.php'</script>";
        exit();
    }else{
        echo "<script>alert('Não foi possivel cadastrar o produto, Tente novamente!');window.location.href='cadastro_produto.php'</script>";
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produtos</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once "sidebar.php";  ?>
    <hr>
    <h2>Cadastrar Produtos</h2>
    <hr>
    <form action="cadastro_produto.php" method="POST">
        <label for="nome_produto">Nome do produto:</label>
        <input type="text" name="nome_prod" class="form-control" required minlength="3">

        <label for="descricao_produto">Descrição do produto:</label>
        <input type="text" name="descricao" class="form-control">

        <label for="quantidade_produto">Quantidade do Produto</label>
        <input type="number" name="qtde" min="0" class="form-control">

        <label for="valor_unitario">Valor Unitario</label>
        <input type="text" name="valor_unit" class="form-control" min="0" step="0.1">

        <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>

        <a href="principal.php">Voltar</a>
        <address>Yan Carlos de Oliveira - Desenvolvimento de Sistemas - Senai</address>
</body>
</html>