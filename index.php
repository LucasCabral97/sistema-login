<?php
require("config/conexao.php");
if (isset($_SESSION['TOKEN'])) {
    $user = auth($_SESSION['TOKEN']);
    if ($user) {
        header("location: restrita.php?option=logado");
    }
}

if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    //declaração para a informação de cadastro novo aparecer apenas umas vez
    $_GET['result'] = "";

    //recebendo os dados do post e tratar
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);

    //verificar se existe esse usuário
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email, $senha_cript));

    $usuario = $sql->fetch(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC, esta pedindo para rertornar uma matriz associativa


    if ($usuario) {
        //existe um usuário

        //agora verificar se o usuário foi confirmado
        if ($usuario['status'] == "confirmado") {
            //criar um token para o usúario
            $token = sha1(uniqid() . date('d-m-Y-H-i-s'));

            //atualizar o token desse usuário no banco
            $sql = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND senha=?");
            if ($sql->execute(array($token, $email, $senha_cript))) {
                //armazenar este token na sessao (SESSION)
                $_SESSION['TOKEN'] = $token; //----> sessão iniciada na config/conexao.php

                //redirecionando para outra página
                header('location: restrita.php');
            }
        } else {
            $erro_login = "Por favor confirme o cadastro no seu <br>e-mail cadastrado!";
        }
    } else {
        $erro_login = "Usuário e/ou senha incorretos!";
    }
}


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

        <?php if (isset($_GET['result']) && $_GET['result'] == 'ok') { ?>
            <div class="sucesso animate__animated animate__rubberBand">
                Cadastrado com sucesso!
            </div>
        <?php } ?>

        <?php if (isset($erro_login)) { ?>
            <div style="text-align:center;" class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_login; ?>
            </div>
        <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>

        <button class="btn-blue" type="submit" name="login">Fazer login</button>
        <a href="cadastrar.php">Ainda não tenho cadastro</a>

    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <!-- só vai ser executado se a div sucesso estiver ativa -->
    <?php if (isset($_GET['result']) && $_GET['result'] == 'ok') { ?>
        <script>
            //escondendo a informação de cadastrado com sucesso
            setTimeout(() => {
                $('.sucesso').hide();
            }, 4000);
        </script>
    <?php } ?>

    <!-- só vai ser executado se a div de erro estiver ativa -->
    <?php if (isset($erro_login)) { ?>
        <script>
            //escondendo a informação de cadastrado com sucesso
            setTimeout(() => {
                $('.erro-geral').addClass('oculto');
            }, 4000);
        </script>
    <?php } ?>

</body>

</html>