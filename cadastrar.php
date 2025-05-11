<?php
    require("config/conexao.php");

    //verificando se existe postagem de acorodo com os campos
    if(isset($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){

        //verificar se os campos foram preenchidos corretamente
        if(empty($_POST['nome_completo']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['repete_senha']) || empty($_POST['termos'])){
            $erro_geral = "Todos os campos são obrigatórios!";
        }else{
            
            //recebendo e tratando os dados que estão vindo do POST
            $nome = limparPost($_POST['nome_completo']);
            $email = limparPost($_POST['email']);
            $senha = limparPost($_POST['senha']);
            $repete_senha = limparPost($_POST['repete_senha']);
            $checkbox = limparPost($_POST['termos']);

            //verificar se o nome está correto
            if (!preg_match("/^[a-zA-Z-' áÁãÃéÉíÍóÓúÚ]*$/",$nome)) {
                $erro_nome = "Somente permitido letras e espaços em branco!";
            }

            //verificar se o email é valido
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erro_email = "Formato de email inválido!";
            }

            //verificar se a senha esta correta
            if(strlen($senha)<6){
                $erro_senha = "Senha deve ter 6 caracteres ou mais!";
            }

            //verificar se as senhas conferem
            if($senha!=$repete_senha){
                $erro_repete_senha = "Senha e repetição de senha diferentes";
            }

            //verificar se o checkbox foi marcado
            if($checkbox!="ok"){
                $erro_checkbox = "Desativado!";
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

        <?php if(isset($erro_geral)){?>
            <div class="erro-geral animate__animated animate__rubberBand">
            <?php echo $erro_geral;?>
            </div>
        <?php } ?>

        <!-- campo nome -->
        <div class="input-group ">
            <img class="input-icon" src="img/card.png">
            <input <?php if(isset($erro_geral) || isset($erro_nome)){echo 'class="erro-input"';}?> type="text" name="nome_completo" placeholder="Nome Completo" required>
            
            <!-- se houver será apresentando o erro desse input -->
            <?php if(isset($erro_nome)){?>
                <div class="erro">
                    <?php echo $erro_nome?>
                </div>
            <?php }?>
        </div>

        <!-- campo email -->
        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input <?php if(isset($erro_geral) || isset($erro_email)){echo 'class="erro-input"';}?> type="email" name="email" placeholder="Digite seu email" required>
            
            <!-- se houver será apresentando o erro desse input -->
            <?php if(isset($erro_email)){?>
                <div class="erro">
                    <?php echo $erro_email?>
                </div>
            <?php }?>
        </div>

        <!-- campo senha -->
        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if(isset($erro_geral) || isset($erro_senha)){echo 'class="erro-input"';}?> type="password" name="senha" placeholder="Senha mínimo 6 digitos" required>
            
            <!-- se houver será apresentando o erro desse input -->
            <?php if(isset($erro_senha)){?>
                <div class="erro">
                    <?php echo $erro_senha?>
                </div>
            <?php }?>
        </div>

        <!-- campo repetição senha -->
        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input <?php if(isset($erro_geral) || isset($erro_repete_senha)){echo 'class="erro-input"';}?> type="password" name="repete_senha" placeholder="Repita a senha" required>
           
            <!-- se houver será apresentando o erro desse input -->
            <?php if(isset($erro_repete_senha)){?>
                <div class="erro">
                    <?php echo $erro_repete_senha?>
                </div>
            <?php }?>
        </div>

        <!-- campo termos -->
        <div <?php if(isset($erro_geral) || isset($erro_checkbox)){echo 'class="erro-input input-group"';}else{echo 'class="input-group"';}?>>
            <input type="checkbox" name="termos" id="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de
                    Privacidade</a> e os
            <a class="link" href="#">Termos de uso</a>.</label>
            
            <!-- se houver será apresentando o erro desse input -->
            <?php if(isset($erro_checkbox)){?>
                <div class="erro">
                    <?php echo $erro_checkbox?>
                </div>
            <?php }?>
        </div>

        <!-- ações -->
        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>

    </form>

</body>

</html>