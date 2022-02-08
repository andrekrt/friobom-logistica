<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false &&  $_SESSION['tipoUsuario'] !=  3){

    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $medida = filter_input(INPUT_POST, 'medida');

    $inserir = $db->prepare("INSERT INTO peca_reparo (descricao, categoria, un_medida) VALUES (:descricao, :categoria, :medida)" );
    $inserir->bindValue(':descricao', $descricao);
    $inserir->bindValue(':categoria', $categoria);
    $inserir->bindValue(':medida', $medida);
    if($inserir->execute()){
        echo "<script> alert('Cadastrado com Sucesso!')</script>";
        echo "<script> window.location.href='pecas.php' </script>";
    }else{
        print_r($inserir->errorInfo());
    }
   
}else{

    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='pecas.php' </script>";

}

?>