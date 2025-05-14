<?php
    require('config/conexao.php');

    // verificar se tem autorização
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
    $sql->execute(array($_SESSION['TOKEN']));

    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    // se não encontrar o usuario
    if(!$usuario){
        header("location: index.php");
    }else{
        echo "<h1> SEJA BEM-VINDO <b style='color:red'>".$usuario['nome']."!</b></h1>";
        echo "<br><br><a style='background:green; text-decoration:none; color:white; padding:20px; border-radius:5px;' href='logout.php'>Sair do sistema</a>";
    }
?>