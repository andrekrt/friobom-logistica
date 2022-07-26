<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idUsuario = $_SESSION['idUsuario'];

    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');
    // echo $descricao;

    $verificaServico = $db->prepare("SELECT * FROM servicos_almoxarifado WHERE descricao = :descricao");
    $verificaServico->bindValue(':descricao', $descricao);
    $verificaServico->execute();
    if($verificaServico->rowCount()>0){

        echo "<script>alert('Esse Serviço já está cadastrada no Estoque!');</script>";
        echo "<script>window.location.href='servicos.php'</script>";

    }else{

        $inserir = $db->prepare("INSERT INTO servicos_almoxarifado (descricao, categoria, usuario) VALUES (:descricao, :categoria, :usuario)");
        $inserir->bindValue(':descricao', $descricao);
        $inserir->bindValue(':categoria', $categoria);
        $inserir->bindValue(':usuario', $idUsuario);

        if($inserir->execute()){
            echo "<script>alert('Serviço Cadastrado com Sucesso!');</script>";
            echo "<script>window.location.href='servicos.php'</script>";
        }else{
            print_r($inserir->errorInfo());
        }

    }

    

}

?>