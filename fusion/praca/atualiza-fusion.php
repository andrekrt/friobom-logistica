<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99 || $_SESSION['tipoUsuario']==1)){

    $id = filter_input(INPUT_POST, 'idfusion');
    $saida = filter_input(INPUT_POST, 'saida');
    $termino = filter_input(INPUT_POST, 'termino');
    $chegada = filter_input(INPUT_POST, 'chegada');
    $carregamento = filter_input(INPUT_POST, 'carga');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $rota = filter_input(INPUT_POST, 'rota');
    $ajudante = filter_input(INPUT_POST, 'ajudante');
    $numEntregas = filter_input(INPUT_POST, 'numEntregas');
    $entregasFeitas = filter_input(INPUT_POST, 'entregasFeita');
    $numDev = filter_input(INPUT_POST, 'numDev');
    $entregasLiq = $entregasFeitas-$numDev;
    $erros = filter_input(INPUT_POST, 'erros');
    $devSemAvisar = filter_input(INPUT_POST, 'devSemAvisar');
    $percDev = $numDev>0?0:1;
    $percFusion = $numEntregas==$entregasFeitas?1:0;
    $percContas = filter_input(INPUT_POST, 'prestaConta');
    $percRota = filter_input(INPUT_POST, 'prazo');
    $premiopossivel = $numEntregas*0.7;
    $situacao = filter_input(INPUT_POST, 'situacao');
   
    if($devSemAvisar>0){
        $premioReal = 0;
    }else{
        $premioReal= ($percDev*0.15+$percFusion*0.2+$percContas*0.15+$percRota*0.2)* $entregasLiq;
    }
    $percPremio = ($premioReal/$premiopossivel);

    // echo "ID: $id<BR>";
    // echo "Saída: $saida<br>";
    // echo "Termino: $termino<br>";
    // echo "Chegada: $chegada<br>";
    // echo "Carregamento: $carregamento<br>";
    // echo "Veículo: $veiculo<br>";
    // echo "Ajudante: $ajudante<br>";
    // echo "Rota: $rota<br>";
    // echo "Nº de Entregas: $numEntregas<br>";
    // echo "Entregas Feitas: $entregasFeitas<br>";
    // echo "Nº Devoluções: $numDev<br>";
    // echo "Entregas Líquida: $entregasLiq<br>";
    // echo "Erro no Fusion: $erros<br>";
    // echo "Uso do Fusion: $erros<br>";
    // echo "Devolução sem Avisar: $devSemAvisar<br>";
    // echo "% Devolução: $percDev<br>";
    // echo "% Entregas no Fusion: $percFusion<br>";
    // echo "% Prestaçaõ de Contas: $percContas<br>";
    // echo "% Dias em Rota: $percRota<br>";
    // echo "Prêmio Possível: $premiopossivel<br>";
    // echo "Prêmio Real: $premioReal<br>";
    // echo "Prêmio Alcançado: $percPremio<br>";

    $sql = $db->prepare("UPDATE fusion_praca SET data_saida = :saida, data_finalizacao = :termino_rota, data_chegada = :chegada_empresa, carga = :carregamento, veiculo=:veiculo, ajudante =:ajudante, rota=:rota, num_entregas=:numEntregas, entregas_ok=:entregas, num_devolucao=:numDev, entregas_liq=:entregasLiq, num_uso_incorreto=:erros,   devolucao_sem_aviso=:devSemAviso, perc_devolucao=:percDev, perc_entregas=:percEntrega, perc_contas=:percConta, premio_possivel=:premioPossivel, premio_real=:premio_ganho, perc_premio=:percPremio, situacao=:situacao WHERE idfusion_praca = :id");
    $sql->bindValue(':saida', $saida);
    $sql->bindValue(':termino_rota', $termino);
    $sql->bindValue(':chegada_empresa', $chegada);
    $sql->bindValue(':carregamento', $carregamento);
    $sql->bindValue(':veiculo', $veiculo);
    $sql->bindValue(':ajudante', $ajudante);
    $sql->bindValue(':rota', $rota);
    $sql->bindValue(':numEntregas', $numEntregas);
    $sql->bindValue(':entregas', $entregasFeitas);
    $sql->bindValue(':erros', $erros);
    $sql->bindValue(':numDev', $numDev);
    $sql->bindValue(':entregasLiq', $entregasLiq);
    $sql->bindValue(':devSemAviso', $devSemAvisar);
    $sql->bindValue(':percDev', $percDev);
    $sql->bindValue(':percEntrega', $percFusion);
    $sql->bindValue(':percConta', $percContas);
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