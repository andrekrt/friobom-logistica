<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==99){

    $token = filter_input(INPUT_GET, 'token');

    echo $token;

    $delete = $db->query("DELETE FROM metas WHERE token = '$token' ");

    if($delete){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='metas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>