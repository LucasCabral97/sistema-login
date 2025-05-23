<?php
require('config/conexao.php');

// // verificar se tem autorização na ****propria página****
// $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
// $sql->execute(array($_SESSION['TOKEN']));

// $usuario = $sql->fetch(PDO::FETCH_ASSOC);

// // se não encontrar o usuario
// if(!$usuario){
//     header("location: index.php");
// }else{
//     echo "<h1> SEJA BEM-VINDO <b style='color:red'>".$usuario['nome']."!</b></h1>";
//     echo "<br><br><a style='background:green; text-decoration:none; color:white; padding:20px; border-radius:5px;' href='logout.php'>Sair do sistema</a>";
// }


//verificar se tem autorização ***função na conexao.php***
$user = auth($_SESSION['TOKEN']);
if ($user) {
    $nomeUsuario = $user['nome'];
} else {
    //redirecionar para o login
    header("location: index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/estilo.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Restrita</title>
</head>

<body style="background: #eeeeee;">
    <nav class="navbar navbar-expand-lg" style="background-color:white;" data-bs-theme="light">
        <div class="container-fluid">
            <div class="text-success fs-4">Seja bem vindo <b><?php echo $nomeUsuario; ?></b>!</div>
            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Alterar Cadastro</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Option 1</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Option 2</a></li>
                </ul>
                <form class="d-flex"><a class="btn btn-outline-danger" href='logout.php'>Sair do sistema</a></form>
            </div>
        </div>
    </nav>

    <?php
    //exibe informação caso o usuário queira entrar na 1ª pagina
    if (isset($_GET['option']) && $_GET['option'] == "logado") {
        if ($_GET['option'] == "logado") {
            echo "<p class='m-1'><b>INFO:</b></p>";
            echo "<p class='text-danger m-2'>Atenção!<br>Usuário logado, caso queira entrar com outro usuário clique em Sair do Sistema!</p>";
        }
    }
    ?>

    <form method="post">
        <div>

        </div>
    </form>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>
</body>

</html>