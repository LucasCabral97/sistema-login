<?php
    // dois modos possíveis -> local, producao
    $modo = 'local';

    if($modo=='local'){
        $servidor = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "login";
    }

    if($modo=='producao'){
        $servidor = "";
        $usuario = "";
        $senha = "";
        $banco = "";
    }


    try{
        
        $pdo = new PDO("mysql:host=$servidor;dbname=$banco" , $usuario,$senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    }catch(PDOException $erro){
        echo "Falha ao se conectar com o banco!";
    }

?>