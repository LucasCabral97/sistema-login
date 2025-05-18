<?php
// dois modos possíveis -> local, producao
session_start();

$modo = 'local';

if ($modo == 'local') {
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "login";
}

if ($modo == 'producao') {
    $servidor = "localhost";
    $usuario = "";
    $senha = "";
    $banco = "login";
}


try {

    $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    echo "Falha ao se conectar com o banco! " . $erro->getMessage();
}


//função para tratar os dados no back-end
function limparPost($dados)
{
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);

    return $dados;
}

function auth($token)
{
    global $pdo;
    // verificar se tem autorização
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
    $sql->execute(array($token));

    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    // se não encontrar o usuario
    if (!$usuario) {
        return false;
    } else {
        return $usuario;
    }
}
