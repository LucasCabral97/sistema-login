<?php
require("config/conexao.php");

// requiremento do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

//verificando se existe postagem de acorodo com os campos

if (isset($_GET['cod']) && !empty($_GET['cod'])) {
    //pegando o código de recuperação
    $codigo = limparPost($_GET['cod']);

    if (isset($_POST['senha']) && isset($_POST['repete_senha'])) {

        //verificar se os campos foram preenchidos corretamente
        if (empty($_POST['senha']) || empty($_POST['repete_senha'])) {
            $erro_geral = "Todos os campos são obrigatórios!";
        } else {

            //recebendo e tratando os dados que estão vindo do POST
            $senha = limparPost($_POST['senha']);

            // ****criptografando a senha******
            $senha_cript = sha1($senha);

            $repete_senha = limparPost($_POST['repete_senha']);

            //verificar se a senha esta correta
            if (strlen($senha) < 6) {
                $erro_senha = "Senha deve ter 6 caracteres ou mais!";
            }

            //verificar se as senhas conferem
            if ($senha != $repete_senha) {
                $erro_repete_senha = "Senha e repetição de senha diferentes";
            }


            if (!isset($erro_repete_senha) && !isset($erro_senha)) {

                //verificar se essa recuperação de senha existe
                $sql = $pdo->prepare("SELECT * FROM usuarios WHERE recupera_senha=? LIMIT 1");
                $sql->execute(array($codigo));

                $usuario = $sql->fetch(PDO::FETCH_ASSOC);

                //se não existir usuario
                if (!$usuario) {
                    $erro_geral = "Recuperação de senha inválida!";
                } else {
                    //mudar senha
                    $sql = $pdo->prepare("UPDATE usuarios SET senha=? WHERE recupera_senha=?");
                    if ($sql->execute(array($senha_cript, $codigo))) {
                        header('location: index.php');
                    }
                }
            }
        }
    }
} else {
    header('location: index.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link href="css/estilo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>

<body>

    <form method="post">
        <h1>Trocar a senha</h1>

        <?php if (isset($erro_geral)) { ?>
            <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_geral; ?>
            </div>
        <?php } ?>


        <!-- campo senha -->
        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if (isset($erro_geral) || isset($erro_senha)) {
                        echo 'class="erro-input"';
                    } ?> type="password"
                name="senha" placeholder="Nova Senha 6 digitos" required>

            <!-- se houver será apresentando o erro desse input -->
            <?php if (isset($erro_senha)) { ?>
                <div class="erro" style="font-size: 13px">
                    <?php echo $erro_senha ?>
                </div>
            <?php } ?>
        </div>

        <!-- campo repetição senha -->
        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input <?php if (isset($erro_geral) || isset($erro_repete_senha)) {
                        echo 'class="erro-input"';
                    } ?>
                type="password" name="repete_senha" placeholder="Repita a nova senha" required>

            <!-- se houver será apresentando o erro desse input -->
            <?php if (isset($erro_repete_senha)) { ?>
                <div class="erro" style="font-size: 13px">
                    <?php echo $erro_repete_senha ?>
                </div>
            <?php } ?>
        </div>

        <!-- ações -->
        <button class="btn-blue" type="submit">Alterar a senha</button>

    </form>

</body>

</html>