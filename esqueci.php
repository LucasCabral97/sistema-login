<?php
require("config/conexao.php");

// requiremento do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = limparPost($_POST['email']);
    $status = "confirmado";

    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND status=?");
    $sql->execute(array($email, $status));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        //existe usuario
        //enviar email para o usuário
        $mail = new PHPMailer(true);
        $cod = sha1(uniqid());

        //atualizar o código recupera senha desse usuário no banco
        $sql = $pdo->prepare("UPDATE usuarios SET recupera_senha=? WHERE email=?");
        if ($sql->execute(array($cod, $email))) {

            try {

                //Remetente e Destinatário
                $mail->setFrom('sistema@developerlcs.com.br', 'Sistema de Login'); //quem esta enviando email
                $mail->addAddress($usuario['email'], $usuario['nome']); //quem vai receber o email

                $mail->CharSet = "UTF-8";

                //Conteudo do email
                $mail->isHTML(true);     //corpo do email como HTML
                $mail->Subject = 'Recuperação de senha'; //Titulo do email
                $mail->Body    = '<h1>Clique abaixo para recuperar a senha:</h1><br>
                                                  <a style="background:red; text-decoration:none; color:white; padding:20px; border-radius:5px;" href="https://developerlcs.com.br/sistema-login/recuperar-senha.php?cod=' . $cod . '">Recuperar a Senha.</a><br><br>
                                                  <p>Atenciosamente;</p>
                                                  <p>Equipe do login.</p>'; //corpo do email

                $mail->send();
                header("location: email-enviado-recupera.html");
            } catch (Exception $e) {
                echo "Houve um problema ao enviar o e-mail!<br>{$mail->ErrorInfo}";
            }
        }
    } else {

        $erro_usuario = "Houve uma falha ao buscar este e-mail. Tente novamente.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a senha</title>
    <link href="css/estilo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>

<body>

    <form method="post">
        <h1>Recuperar Senha</h1>

        <?php if (isset($erro_usuario)) { ?>
            <div style="text-align:center;" class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_usuario; ?>
            </div>
        <?php } ?>

        <p>Informe o e-mail cadastrado no sistema</p>
        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>

        <button class="btn-blue" type="submit" name="login">Recuperar a senha</button>
        <a href="index.php">Voltar para o login</a>

    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <!-- só vai ser executado se a div de erro estiver ativa -->
    <?php if (isset($erro_usuario)) { ?>
        <script>
            //escondendo a informação de cadastrado com sucesso
            setTimeout(() => {
                $('.erro-geral').addClass('oculto');
            }, 4000);
        </script>
    <?php } ?>

</body>

</html>