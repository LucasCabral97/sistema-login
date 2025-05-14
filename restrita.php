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
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>