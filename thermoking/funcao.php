<?php

function atualizaTK($idTk){
    include('../conexao.php');

    $consultaTk =  $db->prepare("SELECT * FROM thermoking WHERE idthermoking = :idTK"); 
    $consultaTk->bindValue(':idTK', $idTk);
    $consultaTk->execute();   
    
    $tk = $consultaTk->fetch();

    $horimetroRevisao = $tk['hora_ultima_revisao'];
    $horaAtual = $tk['hora_atual'];
    $horaUltimaRevisao = $tk['hora_ultima_revisao'];
    $dataRevisao = new DateTime($tk['ultima_revisao_tk']);
    $dataAtual = new DateTime(date("Y-m-d"));
    $diferencaData = $dataRevisao->diff($dataAtual);
    $diferencaData = $diferencaData->y;
    $horaRestante = $horaAtual - $horaUltimaRevisao;
    if($horaRestante>=800 || $diferencaData>=1){
        $situacao = "Pronto para Revisão";
    }else{
        $situacao = "Aguardando";
    }

    $atualizaRevisao = $db->prepare("UPDATE thermoking SET hora_restante = :horimetroFalta, situacao = :situacao WHERE idthermoking = :idTk");
    $atualizaRevisao->bindValue(':horimetroFalta', $horaRestante);
    $atualizaRevisao->bindValue(':situacao', $situacao);
    $atualizaRevisao->bindValue(':idTk', $idTk);
    if($atualizaRevisao->execute()){
        return true;
    }else{
        print_r($atualizaRevisao->errorInfo());
    }

}

function calculoTk($veiculo){
    include "../conexao.php";
    $select = $db->prepare("SELECT * FROM thermoking WHERE veiculo = :veiculo");
    $select->bindValue(":veiculo", $veiculo);
    $select->execute();
    $tks = $select->fetch();
    $hrRestante = $tks['hora_atual']-$tks['hora_ultima_revisao'];
    $dataRevisao = new DateTime($tks['ultima_revisao_tk']);
    $dataAtual = new DateTime(date("Y-m-d"));
    $diferencaData = $dataRevisao->diff($dataAtual);
    $diferencaData = $diferencaData->y;
    
    if(($hrRestante>=800) OR ($diferencaData>=1)){
        $situacao = "Pronto para Revisão";
    }else{
        $situacao = "Aguardando";
    }

    $atualiza = $db->prepare("UPDATE thermoking SET hora_restante = :hrRestante, situacao = :situacao WHERE veiculo = :veiculo");
    $atualiza->bindValue(':hrRestante', $hrRestante);
    $atualiza->bindValue(':situacao', $situacao);
    $atualiza->bindValue(':veiculo', $veiculo);

    if($atualiza->execute()){
        return true;
    }else{
        print_r($db->errorInfo());
    }
}


?>