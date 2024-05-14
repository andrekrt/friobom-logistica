<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

$idModudulo = 1;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $placa = filter_input(INPUT_POST,'placa');
    $kmRevisao = filter_input(INPUT_POST, 'kmRevisao');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $tipoRevisao = filter_input(INPUT_POST, 'tipoRevisao');
    
    $db->beginTransaction();

    try{
        $inserir = $db->prepare("INSERT INTO revisao_veiculos (placa_veiculo, km_revisao, data_revisao, tipo_revisao, filial) VALUES (:placa, :kmRevisao, :dataRevisao, :tipoRevisao, :filial)");
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':kmRevisao', $kmRevisao);
        $inserir->bindValue(':dataRevisao', $dataRevisao);
        $inserir->bindValue(':tipoRevisao', $tipoRevisao);
        $inserir->bindValue(':filial', $filial);

        if($tipoRevisao=='Diferencial'){
            $atualizaVeiculo = $db->prepare("UPDATE veiculos SET km_revisao_diferencial = :kmRevisao, data_revisao_diferencial = :dataRevisao WHERE placa_veiculo = :placa");
            $atualizaVeiculo->bindValue(':kmRevisao', $kmRevisao);
            $atualizaVeiculo->bindValue(':dataRevisao', $dataRevisao);
            $atualizaVeiculo->bindValue(':placa', $placa);
            $atualizaVeiculo->execute();
        }else{
            $atualizaVeiculo = $db->prepare("UPDATE veiculos SET km_ultima_revisao = :kmRevisao, data_revisao_oleo = :dataRevisao WHERE placa_veiculo = :placa");
            $atualizaVeiculo->bindValue(':kmRevisao', $kmRevisao);
            $atualizaVeiculo->bindValue(':dataRevisao', $dataRevisao);
            $atualizaVeiculo->bindValue(':placa', $placa);
            $atualizaVeiculo->execute();
        }

        $inserir->execute();

        $db->commit();
        $_SESSION['msg'] = 'Revisão Lançado com Sucesso';
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