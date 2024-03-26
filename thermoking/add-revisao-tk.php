<?php

session_start();
require("../conexao.php");
include("funcao.php");
date_default_timezone_set('America/Sao_Paulo');

$idModudulo = 2;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $tk = filter_input(INPUT_POST,'tk');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $horimetro = filter_input(INPUT_POST, 'horimetro');
    
    $db->beginTransaction();

    try{
        $inserir = $db->prepare("INSERT INTO revisao_tk (thermoking, data_revisao_tk, horimetro_revisao) VALUES (:thermoking, :dataRevisao, :horimetro)");
        $inserir->bindValue(':thermoking', $tk);
        $inserir->bindValue(':dataRevisao', $dataRevisao);
        $inserir->bindValue(':horimetro', $horimetro);
        $inserir->execute();   

        $atualizarTk = $db->prepare("UPDATE thermoking SET hora_atual = :horaAtual, hora_ultima_revisao = :horimetroRevisao, ultima_revisao_tk = :dataRevisao WHERE idthermoking = :idtk ");
        $atualizarTk->bindValue(':horaAtual', $horimetro);
        $atualizarTk->bindValue(':horimetroRevisao', $horimetro);
        $atualizarTk->bindValue(':dataRevisao', $dataRevisao);
        $atualizarTk->bindValue(':idtk', $tk);
        $atualizarTk->execute();

        $db->commit();

        $_SESSION['msg'] = 'Revisão Lançada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Revisão';
        $_SESSION['icon']='error';
    }

    header("Location: revisao.php");
    exit();

}

?>