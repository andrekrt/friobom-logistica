<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99){

    $idordemServico = filter_input(INPUT_POST, 'idOrdemServico');
    $placa = filter_input(INPUT_POST, 'placa');
    $problema = filter_input(INPUT_POST, 'problema');
    $manutencao = filter_input(INPUT_POST, 'manutencao');
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

    $atualiza = $db->prepare("UPDATE ordem_servico SET placa = :placa, descricao_problema = :problema, tipo_manutencao = :tipoManutencao, causador = :causador, situacao = :situacao, data_encerramento = :dataEncerramento, requisicao_saida = :requisicaoSaida, solicitacao_peca = :solicitacaoPeca, num_nf = :nf, obs = :obs WHERE idordem_servico = :idordemServico");
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':problema', $problema);
    $atualiza->bindValue(':tipoManutencao', $manutencao);
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