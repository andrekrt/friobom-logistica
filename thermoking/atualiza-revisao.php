<?php

session_start();
require("../conexao.php");
include("funcao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 99 ){
    
    $idRevisao = filter_input(INPUT_POST, 'id');
    $tk = filter_input(INPUT_POST,'tk');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    $horimetro = filter_input(INPUT_POST, 'horimetro');
    
    // echo "$idRevisao<br> $tk<br>$dataRevisao<br>$horimetro";

    $atualiza = $db->prepare("UPDATE revisao_tk SET  thermoking = :tk, data_revisao_tk = :dataRevisao, horimetro_revisao = :horaRevisao WHERE idrevisao = :id");
    $atualiza->bindValue(':tk', $tk);
    $atualiza->bindValue(':dataRevisao', $dataRevisao);
    $atualiza->bindValue(':horaRevisao', $horimetro);
    $atualiza->bindValue(':id', $idRevisao);   

    if($atualiza->execute()){
        $atualizarTk = $db->prepare("UPDATE thermoking SET hora_atual = :horaAtual, hora_ultima_revisao = :horimetroRevisao, ultima_revisao_tk = :dataRevisao WHERE idthermoking = :idtk ");
        $atualizarTk->bindValue(':horaAtual', $horimetro);
        $atualizarTk->bindValue(':horimetroRevisao', $horimetro);
        $atualizarTk->bindValue(':dataRevisao', $dataRevisao);
        $atualizarTk->bindValue(':idtk', $tk);
        if($atualizarTk->execute()){
            atualizaTK($tk);
            echo "<script>alert('Revisão Atualizada!');</script>";
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