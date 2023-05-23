<?php

session_start();
require("../conexao.php");
include './funcoes.php';

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99 || $_SESSION['tipoUsuario']==1)){

    $id = filter_input(INPUT_POST, 'id');
    $saida = filter_input(INPUT_POST, 'saida');
    $termino = filter_input(INPUT_POST, 'termino');
    $chegada = filter_input(INPUT_POST, 'chegada');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $rota = filter_input(INPUT_POST, 'rota');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $numEntregas = filter_input(INPUT_POST, 'numEntregas');
    $entregasFeitas = filter_input(INPUT_POST, 'entregasFeita');
    $erros = filter_input(INPUT_POST, 'erros');
    $numDev = filter_input(INPUT_POST, 'numDev');
    $devolucao = filter_input(INPUT_POST, 'devolucao');
    $diasRota = filter_input(INPUT_POST, 'diasRota');
    $velMax = filter_input(INPUT_POST, 'velMax');
    $situacao = filter_input(INPUT_POST, 'situacao');

    $entregasLiq = $entregasFeitas-$numDev;
    $usoFusion = $numEntregas==$entregasFeitas?1:0;
    $checklist=checklist($carregamento);
    if(!is_numeric($checklist)){
        echo $checklist;
        exit;
    }

    $consumo = mediaConsumo($carregamento);
    if(!is_numeric($consumo)){
        echo $consumo;
        exit;
    }

    $premiopossivel = $entregasLiq*1;
   
    if($usoFusion==0){
        $premioReal = 0;
    }else{
        $premioReal= ($usoFusion*0.5+$checklist*0.1+$consumo*0.1+$diasRota*0.1+$devolucao*0.1+$velMax*0.1)* $entregasLiq;
    }

    $percPremio = ($premioReal/$premiopossivel);

    // echo "ID: $id<BR>";
    // echo "Saída: $saida<br>";
    // echo "Termino: $termino<br>";
    // echo "Chegada: $chegada<br>";
    // echo "Carregamento: $carregamento<br>";
    // echo "Veículo: $veiculo";
    // echo "Motorista: $motorista<br>";
    // echo "Rota: $rota<br>";
    // echo "Nº de Entregas: $numEntregas<br>";
    // echo "Entregas Feitas: $entregasFeitas<br>";
    // echo "Erro no Fusion: $erros<br>";
    // echo "Nº Devoluções: $numDev<br>";
    // echo "Entregas Líquida: $entregasLiq<br>";
    // echo "Uso do Fusion: $usoFusion<br>";
    // echo "CheckList: $checklist<br>";
    // echo "Média: $consumo<br>";
    // echo "Devolução: $devolucao<br>";
    // echo "Dias em Rota: $diasRota<br>";
    // echo "Velocidade Máxima: $velMax<br>";
    // echo "Prêmio Possível: $premiopossivel<br>";
    // echo "Prêmio Real: $premioReal<br>";
    // echo "Prêmio Alcançado: $percPremio<br>";
    // echo "Situaçaõ: $situacao<br>";

    $sql = $db->prepare("UPDATE fusion SET saida = :saida, termino_rota = :termino_rota, chegada_empresa = :chegada_empresa, carregamento = :carregamento, veiculo=:veiculo, motorista =:motorista, rota=:rota, num_entregas=:numEntregas, entregas_feitas=:entregas, erros_fusion=:erros, num_dev=:numDev, entregas_liq=:entregasLiq, uso_fusion=:uso, checklist=:checklist, media_km=:media, devolucao=:devolucao, dias_rota=:diasRota, vel_max=:velMax, premio_possivel=:premioPossivel, premio_real=:premio_ganho, premio_alcancado=:percPremio, situacao = :situacao WHERE idfusion = :id");
    $sql->bindValue(':saida', $saida);
    $sql->bindValue(':termino_rota', $termino);
    $sql->bindValue(':chegada_empresa', $chegada);
    $sql->bindValue(':carregamento', $carregamento);
    $sql->bindValue(':veiculo', $veiculo);
    $sql->bindValue(':motorista', $motorista);
    $sql->bindValue(':rota', $rota);
    $sql->bindValue(':numEntregas', $numEntregas);
    $sql->bindValue(':entregas', $entregasFeitas);
    $sql->bindValue(':erros', $erros);
    $sql->bindValue(':numDev', $numDev);
    $sql->bindValue(':entregasLiq', $entregasLiq);
    $sql->bindValue(':uso', $usoFusion);
    $sql->bindValue(':checklist', $checklist);
    $sql->bindValue(':media', $consumo);
    $sql->bindValue(':devolucao', $devolucao);
    $sql->bindValue(':diasRota', $diasRota);
    $sql->bindValue(':velMax', $velMax);
    $sql->bindValue(':premioPossivel', $premiopossivel);
    $sql->bindValue(':premio_ganho', $premioReal);
    $sql->bindValue(':percPremio', $percPremio);
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':id', $id);
    
    if($sql->execute()){
        echo "<script> alert('Atualizado')</script>";
        echo "<script> window.location.href='fusion.php' </script>";  
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>