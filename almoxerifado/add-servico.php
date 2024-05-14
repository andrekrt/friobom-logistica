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
    $filial = $_SESSION['filial'];
    
    $db->beginTransaction();

    try{
        $verificaServico = $db->prepare("SELECT * FROM servicos_almoxarifado WHERE descricao = :descricao");
        $verificaServico->bindValue(':descricao', $descricao);
        $verificaServico->execute();
        if($verificaServico->rowCount()>0){
            $_SESSION['msg'] = 'Esse Serviço já está cadastrada no Estoque!';
            $_SESSION['icon']='warning';
            header("Location: servicos.php");
            exit();

        }

        $inserir = $db->prepare("INSERT INTO servicos_almoxarifado (descricao, categoria, usuario, filial) VALUES (:descricao, :categoria, :usuario, :filial)");
        $inserir->bindValue(':descricao', $descricao);
        $inserir->bindValue(':categoria', $categoria);
        $inserir->bindValue(':usuario', $idUsuario);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $db->commit();

        $_SESSION['msg'] = 'Serviço Cadastrado com Sucesso!';
        $_SESSION['icon']='success';        

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Serviço';
        $_SESSION['icon']='error';
    }
    header("Location: servicos.php");
    exit();
}

?>