<?php
    require('config/conexao.php');
    if(isset($_GET['cod_confirm'])){

        $confirmacao = $_GET['cod_confirm'];
        $sql=$pdo->prepare("SELECT * FROM usuarios WHERE codigo_confirmacao=? LIMIT 1");
        $sql->execute(array($confirmacao));

        $user = $sql->fetch();

        if(!$user){
            echo "Usuário não encontrado!";    
        }else{

            $status = "confirmado";
            $sql=$pdo->prepare("UPDATE usuarios SET status=? WHERE codigo_confirmacao=?");
            $sql->execute(array($status,$confirmacao));

        }

    }else{
        header("location: index.php");
    }

?>