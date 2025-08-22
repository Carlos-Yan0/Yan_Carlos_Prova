<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION["id_usuario"])) {
    echo "<script>alert('Acesso Negado!');window.location.href='index.php';</script>";
    exit();
}

// VERIFICA SE O USUARIO TEM PERMISSAO DE ADM
if ($_SESSION['perfil'] != 1 || !isset($_SESSION["id_usuario"])) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php'</script>";
    exit();
}

// INICIALIZA VARIAVEL
$usuario = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_usuario'])) {
        $busca = trim($_POST['busca_usuario']);

        // VERIFICA SE A BUSCA É UM NÚMERO (id) OU UM NOME
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o usuário não for encontrado, exibe um alerta
        if (!$usuario) {
            echo "<script>alert('Usuário não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar usuário</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">


    <!-- CERTIFIQUE-SE DE QUE O JAVASCRIPT ESTÁ SENDO CARREGADO CORRETAMENTE -->
    <script src="scripts.js"></script>
</head>

<body>
    <?php require_once "sidebar.php";?>
    <hr>
    <h2 class="title">Alterar usuário</h2>
    <hr>
    <form action="alterar_usuario.php" method="POST">
        <label for="busca_usuario">Digite o id ou nome do usuário</label>
        <input type="text" id="busca_usuario" name="busca_usuario" class="form-control" required onkeyup="buscarSugestoes()">

        <!-- DIV PARA EXIBIR SUGESTOES DE USUARIOS -->
        <div id="sugestoes"></div>
        <button type="submit" class="btn btn-success">Buscar</button>
    </form>

    <?php if ($usuario): ?>
        <!-- FORMULARIO PARA ALTERAR USUARIO -->
        <form action="processa_alteracao_usuario.php" method="POST">
            <input type="hidden" name="id_usuario" class="form-control" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required minlength="3" pattern="^[A-Za-zÀ-ÿ\s]+$">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label for="id_perfil">Perfil:</label>
            <select name="id_perfil" id="id_perfil" class="form-select">
                <option value="1" <?= $usuario['id_perfil'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $usuario['id_perfil'] == 2 ? 'selected' : '' ?>>Secretária</option>
                <option value="3" <?= $usuario['id_perfil'] == 3 ? 'selected' : '' ?>>Almoxarife</option>
                <option value="4" <?= $usuario['id_perfil'] == 4 ? 'selected' : '' ?>>Cliente</option>
            </select>

            <!-- SE O USUARIO LOGADO FOR ADM, EXIBIR OPCAO DE ALTERAR SENHA -->
            <?php if ($_SESSION['perfil'] == 1): ?>
                <label for="nova_senha">Nova senha</label>
                <input type="password" name="nova_senha" id="nova_senha" class="form-control" minlength="8">
            <?php endif; ?>

            <button type="submit" class="btn btn-success">Alterar</button>
            <button type="reset" class="btn btn-success">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
    <address>Yan Carlos de Oliveira - Desenvolvimento de Sistemas - Senai</address>
</body>
</html>