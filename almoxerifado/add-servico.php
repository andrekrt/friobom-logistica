<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

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