<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){
    
    $placa = filter_input(INPUT_POST,'placa');
    $kmRevisao = filter_input(INPUT_POST, 'kmRevisao');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $tipoRevisao = filter_input(INPUT_POST, 'tipoRevisao');
    
    //echo "$placa<br>$kmRevisao<br>$dataRevisao";

   $inserir = $db->prepare("INSERT INTO revisao_veiculos (placa_veiculo, km_revisao, data_revisao, tipo_revisao) VALUES (:placa, :kmRevisao, :dataRevisao, :tipoRevisao)");
    $inserir->bindValue(':placa', $placa);
    $inserir->bindValue(':kmRevisao', $kmRevisao);
    $inserir->bindValue(':dataRevisao', $dataRevisao);
    $inserir->bindValue(':tipoRevisao', $tipoRevisao);

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

    if($inserir->execute()){
        echo "<script>alert('Revisão Lançada!');</script>";
        echo "<script>window.location.href='revisao.php'</script>";
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>