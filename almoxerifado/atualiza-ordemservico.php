<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idordemServico = filter_input(INPUT_POST, 'idOrdemServico');
    $placa = filter_input(INPUT_POST, 'placa');
    $problema = filter_input(INPUT_POST, 'problema');
    $corretiva = filter_input(INPUT_POST, 'corretiva')?1:0;
    $preventiva = filter_input(INPUT_POST, 'preventiva')?1:0;
    $externa = filter_input(INPUT_POST, 'externa')?1:0;
    $oleo = filter_input(INPUT_POST, 'oleo')?1:0;
    $higienizacao = filter_input(INPUT_POST, 'higienizacao')?1:0;
    $causador = filter_input(INPUT_POST, 'causador')?filter_input(INPUT_POST, 'causador'):null;
    $requisicaoSaida = filter_input(INPUT_POST, 'requisicao')?filter_input(INPUT_POST, 'requisicao'):null;
    $solicitacaoPeca = filter_input(INPUT_POST, 'solicitacao')?filter_input(INPUT_POST, 'solicitacao'):null;
    $nf = filter_input(INPUT_POST, 'numNf')?filter_input(INPUT_POST, 'numNf'):null;
    $situacao = filter_input(INPUT_POST, 'situacao');
    if($situacao=="Encerrada"){
        $dataEncarrecamento = date("Y-m-d H:i:s");
    }else{
        $dataEncarrecamento = null;
    }
    $obs = filter_input(INPUT_POST, 'obs');

    $atualiza = $db->prepare("UPDATE ordem_servico SET placa = :placa, descricao_problema = :problema, corretiva = :corretiva, preventiva =:preventiva, externa = :externa, oleo = :oleo, higienizacao = :higienizacao, causador = :causador, situacao = :situacao, data_encerramento = :dataEncerramento, requisicao_saida = :requisicaoSaida, solicitacao_peca = :solicitacaoPeca, num_nf = :nf, obs = :obs WHERE idordem_servico = :idordemServico");
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':problema', $problema);
    $atualiza->bindValue(':corretiva', $corretiva);
    $atualiza->bindValue(':preventiva', $preventiva);
    $atualiza->bindValue(':externa', $externa);
    $atualiza->bindValue(':oleo', $oleo);
    $atualiza->bindValue(':higienizacao', $higienizacao);
    $atualiza->bindValue(':causador', $causador);
    $atualiza->bindValue(':situacao', $situacao);
    $atualiza->bindValue(':dataEncerramento', $dataEncarrecamento);
    $atualiza->bindValue(':requisicaoSaida', $requisicaoSaida);
    $atualiza->bindValue(':solicitacaoPeca', $solicitacaoPeca);
    $atualiza->bindValue(':nf', $nf);
    $atualiza->bindValue(':obs', $obs);
    $atualiza->bindValue(':idordemServico', $idordemServico);

    if($atualiza->execute()){
        echo "<script> alert('Atualizsado com Sucesso!')</script>";
        echo "<script> window.location.href='ordem-servico.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>