<?php

session_start();
require("../conexao.php");
include("funcao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 99 ){
    
    $tk = filter_input(INPUT_POST,'tk');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $horimetro = filter_input(INPUT_POST, 'horimetro');
    
    //echo "$tk<br>$dataRevisao<br>$horimetro";

    $inserir = $db->prepare("INSERT INTO revisao_tk (thermoking, data_revisao_tk, horimetro_revisao) VALUES (:thermoking, :dataRevisao, :horimetro)");
    $inserir->bindValue(':thermoking', $tk);
    $inserir->bindValue(':dataRevisao', $dataRevisao);
    $inserir->bindValue(':horimetro', $horimetro);   

    if($inserir->execute()){
        $atualizarTk = $db->prepare("UPDATE thermoking SET hora_atual = :horaAtual, hora_ultima_revisao = :horimetroRevisao, ultima_revisao_tk = :dataRevisao WHERE idthermoking = :idtk ");
        $atualizarTk->bindValue(':horaAtual', $horimetro);
        $atualizarTk->bindValue(':horimetroRevisao', $horimetro);
        $atualizarTk->bindValue(':dataRevisao', $dataRevisao);
        $atualizarTk->bindValue(':idtk', $tk);
        if($atualizarTk->execute()){
            atualizaTK($tk);
            echo "<script>alert('Revisão de Lançada!');</script>";
            echo "<script>window.location.href='revisao.php'</script>";
        }else{
            print_r($atualizarTk->errorInfo());
            
        }
        
    }else{
        print_r($inserir->errorInfo());
        echo "erro aqui";
    }

}

?>