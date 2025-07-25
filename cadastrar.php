<?php
require("config/conexao.php");

// requiremento do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

//verificando se existe postagem de acorodo com os campos
if (isset($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])) {

    //verificar se os campos foram preenchidos corretamente
    if (empty($_POST['nome_completo']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['repete_senha']) || empty($_POST['termos'])) {
        $erro_geral = "Todos os campos são obrigatórios!";
    } else {

        //recebendo e tratando os dados que estão vindo do POST
        $nome = limparPost($_POST['nome_completo']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);

        // ****criptografando a senha******
        $senha_cript = sha1($senha);

        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);

        //verificar se o nome está correto
        if (!preg_match("/^[a-zA-Z-' áÁãÃéÉíÍóÓúÚ]*$/", $nome)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
        }

        //verificar se o email é valido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro_email = "Formato de email inválido!";
        }

        //verificar se a senha esta correta
        if (strlen($senha) < 6) {
            $erro_senha = "Senha deve ter 6 caracteres ou mais!";
        }

        //verificar se as senhas conferem
        if ($senha != $repete_senha) {
            $erro_repete_senha = "Senha e repetição de senha diferentes";
        }

        //verificar se o checkbox foi marcado
        if ($checkbox != "ok") {
            $erro_checkbox = "Desativado!";
        }

        if (!isset($erro_checkbox) && !isset($erro_email) && !isset($erro_geral) && !isset($erro_nome) && !isset($erro_repete_senha) && !isset($erro_senha)) {
            //1º verificar se o usuario ja está cadastrado no banco
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
            $sql->execute(array($email));

            $usuario = $sql->fetch();

            //se não existir usuario - adicionar no banco
            if (!$usuario) {
                //incluir usuário no banco

                $recupera_senha = "";
                $token = ""; //vai ser criado no login
                $codigo_confirmacao = uniqid();
                $status = "novo";
                $data_cadastro = date('d/m/Y');

                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null,?,?,?,?,?,?,?,?)");
                if ($sql->execute(array($nome, $email, $senha_cript, $recupera_senha, $token, $codigo_confirmacao, $status, $data_cadastro))) {

                    //se o modo for local -> variavel esta em config/conexao.php
                    if ($modo == "local") {
                        header('location: index.php?result=ok');
                    }

                    //se o modo for producao -> variavel esta em config/conexao.php
                    if ($modo == "producao") {

                        //enviar email para o usuário
                        $mail = new PHPMailer(true);
                        try {

                            //Remetente e Destinatário
                            $mail->setFrom('sistema@developerlcs.com.br', 'Sistema de Login'); //quem esta enviando email
                            $mail->addAddress($email, $nome); //quem vai receber o email

                            $mail->CharSet = "UTF-8";

                            //Conteudo do email
                            $mail->isHTML(true);     //corpo do email como HTML
                            $mail->Subject = 'Confirme seu cadastro!'; //Titulo do email
                            $mail->Body    = '<h1>Por favor confirme e-mail abaixo:</h1><br>
                                                  <a style="background:green; text-decoration:none; color:white; padding:20px; border-radius:5px;" href="https://developerlcs.com.br/sistema-login/confirmacao.php?cod_confirm=' . $codigo_confirmacao . '">Confirmar E-mail.</a><br><br>
                                                  <p>Atenciosamente;</p>
                                                  <p>Equipe do login.</p>'; //corpo do email

                            $mail->send();
                            header("location: obrigado.html");
                        } catch (Exception $e) {
                            echo "Houve um problema ao enviar o e-mail de confirmação!<br>{$mail->ErrorInfo}";
                        }
                    }
                }
            } else {
                //já existe usuario, apresentar erro
                $erro_geral = "Usuário já cadastrado";
            }
        }
    }
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
        <h1>Cadastrar</h1>

        <?php if (isset($erro_geral)) { ?>
            <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_geral; ?>
            </div>
        <?php } ?>

        <!-- campo nome -->
        <div class="input-group ">
            <img class="input-icon" src="img/card.png">
            <input <?php if (isset($erro_geral) || isset($erro_nome)) {
                        echo 'class="erro-input"';
                    } ?> type="text"
                name="nome_completo" placeholder="Nome Completo"
                <?php if (isset($nome)) {
                    echo "value='$nome'";
                } ?> required>

            <!-- se houver será apresentando o erro desse input -->
            <?php if (isset($erro_nome)) { ?>
                <div class="erro" style="font-size: 13px">
                    <?php echo $erro_nome ?>
                </div>
            <?php } ?>
        </div>

        <!-- campo email -->
        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input <?php if (isset($erro_geral) || isset($erro_email)) {
                        echo 'class="erro-input"';
                    } ?> type="email"
                name="email" placeholder="Digite seu email"
                <?php if (isset($nome)) {
                    echo "value='$email'";
                } ?>required>

            <!-- se houver será apresentando o erro desse input -->
            <?php if (isset($erro_email)) { ?>
                <div class="erro" style="font-size: 13px">
                    <?php echo $erro_email ?>
                </div>
            <?php } ?>
        </div>

        <!-- campo senha -->
        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if (isset($erro_geral) || isset($erro_senha)) {
                        echo 'class="erro-input"';
                    } ?> type="password"
                name="senha" placeholder="Senha mínimo 6 digitos" required>

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
                type="password" name="repete_senha" placeholder="Repita a senha" required>

            <!-- se houver será apresentando o erro desse input -->
            <?php if (isset($erro_repete_senha)) { ?>
                <div class="erro" style="font-size: 13px">
                    <?php echo $erro_repete_senha ?>
                </div>
            <?php } ?>
        </div>

        <!-- campo termos -->
        <div <?php if (isset($erro_geral) || isset($erro_checkbox)) {
                    echo 'class="erro-input input-group"';
                } else {
                    echo 'class="input-group"';
                } ?>>
            <input type="checkbox" name="termos" id="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de
                    Privacidade</a> e os
                <a class="link" href="#">Termos de uso</a>.</label>

            <!-- se houver será apresentando o erro desse input -->
            <?php if (isset($erro_checkbox)) { ?>
                <div class="erro">
                    <?php echo $erro_checkbox ?>
                </div>
            <?php } ?>
        </div>

        <!-- ações -->
        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>

    </form>

</body>

</html>