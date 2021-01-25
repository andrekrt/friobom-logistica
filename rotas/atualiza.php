<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $codRota = filter_input(INPUT_POST, 'codRota');
    $rota = filter_input(INPUT_POST, 'rota');

    $atualiza = $db->query("UPDATE rotas SET nome_rota = '$rota' WHERE cod_rota = '$codRota'");
    if($atualiza){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='rotas.php' </script>";
    }else{
        echo "Erro, contatar o administrador";
    }

}else{
    header("Location:rotas.php");
}

?>