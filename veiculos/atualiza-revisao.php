<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){

    $idRevisao = filter_input(INPUT_POST, 'id');
    $placa = filter_input(INPUT_POST, 'placa');
    $kmRevisao = filter_input(INPUT_POST, 'kmRevisao');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $tipoRevisao = filter_input(INPUT_POST, 'tipoRevisao');

    //echo "$idRevisao<br>$placa<br>$kmRevisao<br>$dataRevisao<br>$tipoRevisao";

    $atualiza = $db->prepare("UPDATE revisao_veiculos SET placa_veiculo = :placa, km_revisao = :kmRevisao, data_revisao = :dataRevisao, tipo_revisao = :tipoRevisao WHERE id = :idRevisao");
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':kmRevisao', $kmRevisao);
    $atualiza->bindValue(':dataRevisao', $dataRevisao);
    $atualiza->bindValue(':idRevisao', $idRevisao);
    $atualiza->bindValue(':tipoRevisao', $tipoRevisao);
    
    if($atualiza->execute()){
        echo "<script> alert('Atualizsado com Sucesso!')</script>";
        echo "<script> window.location.href='revisao.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>