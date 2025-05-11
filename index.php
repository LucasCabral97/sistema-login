<?php
    require("config/conexao.php");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/estilo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>

<body>

    <form method="post">
        <h1>Login</h1>


        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" name="" id="" placeholder="Digite seu email">
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input type="password" name="" id="" placeholder="Digite sua senha">
        </div>

        <button class="btn-blue" type="submit">Fazer login</button>
        <a href="cadastrar.php">Ainda não tenho cadastro</a>

    </form>

</body>

</html>