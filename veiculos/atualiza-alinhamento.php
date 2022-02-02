<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99){

    $idAlinhamento = filter_input(INPUT_POST, 'id');
    $placa = filter_input(INPUT_POST, 'placa');
    $kmAlinhamento = filter_input(INPUT_POST, 'kmAlinhamento');
    $dataAlinhamento = filter_input(INPUT_POST, 'dataAlinhamento');
    $tipoAlinhamento = filter_input(INPUT_POST, 'tipo');

    //echo "$idRevisao<br>$placa<br>$kmRevisao<br>$dataRevisao<br>$tipoRevisao";

    $atualiza = $db->prepare("UPDATE alinhamentos_veiculo SET data_alinhamento = :dataAlinhamento, km_alinhamento = :kmAlinhamento, placa_veiculo = :placa, tipo_alinhamento = :tipo WHERE idalinhamento = :id");
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':kmAlinhamento', $kmAlinhamento);
    $atualiza->bindValue(':dataAlinhamento', $dataAlinhamento);
    $atualiza->bindValue(':id', $idAlinhamento);
    $atualiza->bindValue(':tipo', $tipoAlinhamento);
    
    if($atualiza->execute()){
        $atualizaVeiculo = $db->prepare("UPDATE veiculos SET km_alinhamento = :kmAlinhamento WHERE placa_veiculo = :placa");
        $atualizaVeiculo->bindValue(':placa', $placa);
        $atualizaVeiculo->bindValue(':kmAlinhamento', $kmAlinhamento);
        if($atualizaVeiculo->execute()){
            echo "<script> alert('Atualizado com Sucesso!')</script>";
            echo "<script> window.location.href='alinhamentos.php' </script>";
        }else{
            print_r($atualizaVeiculo->errorInfo());
        }
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>