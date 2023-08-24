<?php
    require_once('classes/Crud.php');
    require_once('conexao/conexao.php');

    $database=new Conexao();
    $db=$database->getConnection();
    $crud=new Crud($db);

    if(isset($_POST['cadastrar'])){
        $nome=$_POST['nome'];
        $email=$_POST['email'];
        $senha=$_POST['senha'];
        $confSenha=$_POST['confSenha'];
        
        if($crud->cadastrar($nome,$email,$senha,$confSenha)){
            echo"Cadastro realizado com sucesso";
            header("Location:index.php");
        }else{
            echo"Erro ao cadastrar!";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h1>Cadastrar</h1>
    <form method="POST">
        <label for="">Nome de usuario</label>
        <input type="text" name="nome" placeholder="Insira o nome de usuario" required>
        <label for="">E-mail</label>
        <input type="email" name="email" placeholder="Insira um E-mail" required>
        <label for="">Senha</label>
        <input type="password" name="senha" placeholder="Insira uma senha" minlength="8" required>
        <label for="">Confirmar senha</label>
        <input type="password" name="confSenha" placeholder="Confirme sua senha" minlength="8" required>
        <button type="submit" name="cadastrar">Cadastrar</button>
    </form>
    <a href="index.php">Voltar para a tela de login</a>
</div>
</body>
</html>