<?php
    require('config/conexao.php');
    if(isset($_GET['cod_confirm']) && !empty($_GET['cod_confirm'])){

        $confirmacao = limparPost($_GET['cod_confirm']);
        $sql=$pdo->prepare("SELECT * FROM usuarios WHERE codigo_confirmacao=? LIMIT 1");
        $sql->execute(array($confirmacao));

        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if($user){
            
            $status = "confirmado";
            $sql=$pdo->prepare("UPDATE usuarios SET status=? WHERE codigo_confirmacao=?");
            $sql->execute(array($status,$confirmacao));
            
            header("location: index.php");

        }else{
            echo "<h1>Código de confirmação inválido!</h1>";
        }
    }else{
        header("location: index.php");
    }
?>