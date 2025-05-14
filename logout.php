<?php
    session_start();//inicia seção nesse arquivo
    session_unset();//limpa a seção
    session_destroy();//elimina a seção
    header("location: index.php");

?>