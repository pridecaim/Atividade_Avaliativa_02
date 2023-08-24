<?php
include('conexao/conexao.php');
$db = new Conexao();

class Crud 
{
    private $conn;
    private $table_name = "crud";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function guardarEmail($email){
        $sql = "SELECT email FROM crud WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
    
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['email'] = $row['email'];
        }
    }
    public function cadastrar($nome, $email, $senha, $confSenha)
    {
        if ($senha === $confSenha) {
            $emailExistente = $this->verificarEmailExistente($email);
            $nomeExistente = $this->verificarNomeExistente($nome);

            if ($emailExistente || $nomeExistente) {
                print "<script>alert('Email e/ou nome já cadastrado')</script>";
                return false;
            }

            $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO " . $this->table_name . " (nome, email, senha) VALUES (?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $email);
            $stmt->bindValue(3, $senhaCriptografada);

            if ($stmt->execute()) {
                print "<script>alert ('Cadastro efetuado com sucesso!')</script>";
                print "<script> location.href='?action=read'; </script>";
                return true;
            }
        } else {
            return false;
        }
    }

    private function verificarEmailExistente($email)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    private function verificarNomeExistente($nome)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE nome=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $nome);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function logar($nome, $senha)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE nome = :nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $crud = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($senha, $crud['senha'])) {
                return true;
            }
        }
        return false;
    }

    public function read()
    {
        $sql = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
    
    public function update($data)
    {
        $id = $data['id'];
        $novoNome = $data['nome'];
        $novoEmail = $data['email'];
        $novaSenha = $data['senha'];

        $novaSenhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);

        $sql = "UPDATE " . $this->table_name . " SET nome=?, email=?, senha=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindValue(1, $novoNome);
        $stmt->bindValue(2, $novoEmail);
        $stmt->bindValue(3, $novaSenhaCriptografada);
        $stmt->bindValue(4, $id);
    
        if ($stmt->execute()) {

            return true;
        } else {

            return false;
        }
    }

    public function readOne($id)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
