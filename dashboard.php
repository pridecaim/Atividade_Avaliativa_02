<?php
require_once('classes/Crud.php');
require_once('conexao/conexao.php');
session_start();

$database = new Conexao();
$db = $database->getConnection();
$crud = new Crud($db);

if (!isset($_SESSION['nome'])) {
    header("Location: index.php");
    exit();
}

$nome = $_SESSION['nome'];
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] == 'update') {
    $id = $_POST['id'];
    $novoNome = $_POST['nome'];
    $novoEmail = $_POST['email'];
    $novaSenha = $_POST['senha'];
    
    // Dados para atualização
    $data = array(
        'id' => $id,
        'nome' => $novoNome,
        'email' => $novoEmail,
        'senha' => $novaSenha
    );
    
    if ($crud->update($data)) {
        echo "Registro atualizado com sucesso.";
        header("Location: dashboard.php"); // Redirecionar após a atualização
        exit();
    } else {
        echo "Erro ao atualizar o registro.";
    }
}

$rows = $crud->read();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styledash.css">
    <style>
        th, td{
            text-align:left;
            padding:8px;
            border: 1px solid #ddd;
         }
        th{
           background-color:#f2f2f2;
           font-weight:bold; 
        }
        a{
            display:inline-block;
            padding:4px 8px;
            background-color: #007bff;
            color:#fff;
            text-decoration:none;
            border-radius:4px;
        }
        a:hover{
            background-color:#0069d9;
        }

        a.delete{
            background-color: #dc3545;
        }
        a.delete:hover{
            background-color:#c82333;
        }</style>
</head>
<body>
<div class="form-container">
    <?php
    if ($rows->rowCount() == 0) {
        echo "<tr>";
        echo "<td colspan='7'>Nenhum dado encontrado</td>";
        echo "</tr>";
    } else {
        while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $result = $crud->readOne($id);

            if (!$result) {
                echo "Registro não encontrado.";
                exit();
            }
            $nome = $result['nome'];
            $email = $result['email'];
            ?>
            <h1>Painel de controle</h1>
            Olá <?php echo $nome; ?>, suas permissões são: <br><br>
            <form action="?action=update" method="POST">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <label for="nome">Nome de Usuário</label>
                <input type="text" name="nome" value="<?php echo $nome ?>">

                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Insira seu novo e-mail" value="<?php echo $email ?>">

                <label for="senha">Nova Senha</label>
                <input type="password" name="senha" value="" placeholder="Crie sua nova senha" minlength="8">

                <button type="submit" value="Atualizar" name='enviar'
                        onclick="return confirm('Certeza que deseja atualizar?')">Atualizar
                </button>
            </form>
            <?php
        }
    }
    ?>
    <div class="botaologout">
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>