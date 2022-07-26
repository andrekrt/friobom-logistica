<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idservico = filter_input(INPUT_POST, 'id');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');

    $atualiza = $db->prepare("UPDATE servicos_almoxarifado SET descricao = :descricao, categoria = :categoria WHERE idservicos = :id");
    $atualiza->bindValue(':descricao', $descricao);
    $atualiza->bindValue(':categoria', $categoria);
    $atualiza->bindValue(':id', $idservico);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='servicos.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "<script> alert('Acesso não permitido')</script>";
    echo "<script> window.location.href='servicos.php' </script>";
}

?>