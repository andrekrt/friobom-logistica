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
    $dataAtual = new DateTime();
    $dataAtual= $dataAtual->format("Y-m-d");
    $horaRestante = $horaAtual - $horaUltimaRevisao;
    if($horaRestante<=800 || $diferencaData<1){
        $situacao = "Aguardando";
    }else{
        $situacao = "Pronto para Revisão";
    }

    $atualizaRevisao = $db->prepare("UPDATE thermoking SET hora_restante = :horimetroFalta, situacao = :situacao WHERE idthermoking = :idTk");
    $atualizaRevisao->bindValue(':horimetroFalta', $horaRestante);
    $atualizaRevisao->bindValue(':situacao', $situacao);
    $atualizaRevisao->bindValue(':idTk', $idTk);
    if($atualizaRevisao->execute()){
        
    }else{
        print_r($atualizaRevisao->errorInfo());
    }

}

atualizaTK(5);

?>