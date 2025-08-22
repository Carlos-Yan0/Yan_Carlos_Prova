<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION["id_usuario"])) {
    echo "<script>alert('Acesso Negado!');window.location.href='index.php';</script>";
    exit();
}

//VERIFICA SE O USUARIO TEM PERMISSAO
//SUPONDO QUE O PERFIL 1 SEJA ADM
if($_SESSION['perfil'] != 1){
    echo "<script>alert('Acesso Negado!');window.locarion.href='principal.php';</script>";
        exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql = "INSERT INTO usuario(nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senha);
    $stmt->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
    
    
    if($stmt->execute()){
        echo "<script>alert('Usuario Cadastrado Com sucesso');</script>";
    }else{
        echo "<script>alert('Erro, não foi possivel cadastrar o usuario');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuario</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once "sidebar.php"; ?>
    <hr>
    <h2>Cadastrar Usuario</h2>
    <hr>
    <form action="cadastro_usuario.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" required minlength="3" pattern="^[A-Za-zÀ-ÿ\s]+$">

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" class="form-control" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" class="form-control"  required minlength="8">

        <label for="id_perfil">Perfil</label>
        <select name="id_perfil" id="id_perfil" class="form-select">
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
        </select>

        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="reset" class="btn btn-success">Cancelar</button>
    </form>
    
    <a href="principal.php">Voltar</a>
    <address>Yan Carlos de Oliveira - Desenvolvimento de Sistemas - Senai</address>
</body>
</html>