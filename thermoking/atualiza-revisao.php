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
    
    $idRevisao = filter_input(INPUT_POST, 'id');
    $tk = filter_input(INPUT_POST,'tk');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $horimetro = filter_input(INPUT_POST, 'horimetro');
    
    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE revisao_tk SET  thermoking = :tk, data_revisao_tk = :dataRevisao, horimetro_revisao = :horaRevisao WHERE idrevisao = :id");
        $atualiza->bindValue(':tk', $tk);
        $atualiza->bindValue(':dataRevisao', $dataRevisao);
        $atualiza->bindValue(':horaRevisao', $horimetro);
        $atualiza->bindValue(':id', $idRevisao);   
        $atualiza->execute();

        $atualizarTk = $db->prepare("UPDATE thermoking SET hora_atual = :horaAtual, hora_ultima_revisao = :horimetroRevisao, ultima_revisao_tk = :dataRevisao WHERE idthermoking = :idtk ");
        $atualizarTk->bindValue(':horaAtual', $horimetro);
        $atualizarTk->bindValue(':horimetroRevisao', $horimetro);
        $atualizarTk->bindValue(':dataRevisao', $dataRevisao);
        $atualizarTk->bindValue(':idtk', $tk);
        $atualizarTk->execute();

        // atualizaTK($tk);

        $db->commit();

        $_SESSION['msg'] = 'Revisão Atualizada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Revisão';
        $_SESSION['icon']='error';
    }

    header("Location: revisao.php");
    exit();

}

?>