<?php
session_start();
require_once('classes/Crud.php');
require_once('conexao/conexao.php');

$database=new Conexao();
$db= $database->getConnection();
$crud=new Crud($db);

if(isset($_POST['logar'])){
    $nome=$_POST['nome'];
    $senha=$_POST['senha'];  
    if($crud->logar($nome,$senha)){
        $_SESSION['nome']=$nome;
        $_SESSION['email'] = $crud->guardarEmail($email); 
        header("Location: dashboard.php");
        exit();
    }else{
        print"<script>alert('Credenciais invalidas')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h1>Login</h1>
<form action="?action=update" method="POST">
    <label for="nome">Nome de usuario</label>
    <input type="text" name="nome" placeholder="Coloque seu Nome de usuario" required>
    <label for="Senha">Senha</label>
    <input type="password" name="senha" placeholder="Coloque sua Senha" required>
    <button type="submit" name="logar">Logar</button>
</form> 
    <a href="cadastrar.php">Clique aqui para criar uma conta</a>
    </div>
</body>
</html>