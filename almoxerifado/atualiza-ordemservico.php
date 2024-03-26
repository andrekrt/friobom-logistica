<?php

session_start();
require("../conexao.php");
include("funcoes.php");
date_default_timezone_set('America/Sao_Paulo');

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idordemServico = filter_input(INPUT_POST, 'idOrdemServico');
    $placa = filter_input(INPUT_POST, 'placa');
    $problema = filter_input(INPUT_POST, 'problema');
    $corretiva = filter_input(INPUT_POST, 'corretiva')?1:0;
    $preventiva = filter_input(INPUT_POST, 'preventiva')?1:0;
    $externa = filter_input(INPUT_POST, 'externa')?1:0;
    $higienizacao = filter_input(INPUT_POST, 'higienizacao')?1:0;
    $causador = filter_input(INPUT_POST, 'causador')?filter_input(INPUT_POST, 'causador'):null;
    $nf = filter_input(INPUT_POST, 'numNf')?filter_input(INPUT_POST, 'numNf'):null;
    $situacao = filter_input(INPUT_POST, 'situacao');
    if($situacao=="Encerrada"){
        $dataEncarrecamento = date("Y-m-d H:i:s");
        alterarStatusCaminhao($placa, "Disponível");
    }else{
        $dataEncarrecamento = null;
    }
    $obs = filter_input(INPUT_POST, 'obs');

    //saida de peças
    $idSaida = $_POST['idsaida'];
    $servicos = $_POST['servico'];
    $pecas = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']);
    $requisicao = $_POST['requisicao'];

    $db->beginTransaction();

    try{
        $i=0;
        for($i; $i<count($qtd);$i++){
            if(contaEstoque($pecas[$i])<$qtd[$i]){
                $_SESSION['msg'] = 'Estoque Insuficiente!';
                $_SESSION['icon']='warning';
                header("Location: ordem-servico.php");
                exit();
            }
        }

        $atualiza = $db->prepare("UPDATE ordem_servico SET placa = :placa, descricao_problema = :problema, corretiva = :corretiva, preventiva =:preventiva, externa = :externa, higienizacao = :higienizacao, causador = :causador, situacao = :situacao, data_encerramento = :dataEncerramento, num_nf = :nf, obs = :obs WHERE idordem_servico = :idordemServico");
        $atualiza->bindValue(':placa', $placa);
        $atualiza->bindValue(':problema', $problema);
        $atualiza->bindValue(':corretiva', $corretiva);
        $atualiza->bindValue(':preventiva', $preventiva);
        $atualiza->bindValue(':externa', $externa);
        $atualiza->bindValue(':higienizacao', $higienizacao);
        $atualiza->bindValue(':causador', $causador);
        $atualiza->bindValue(':situacao', $situacao);
        $atualiza->bindValue(':dataEncerramento', $dataEncarrecamento);
        $atualiza->bindValue(':nf', $nf);
        $atualiza->bindValue(':obs', $obs);
        $atualiza->bindValue(':idordemServico', $idordemServico);
        $atualiza->execute();

        if($externa===1){
            $status = "Manutenção Externa";
        }else{
            $status = "Manutenção Interna";
        }

        alterarStatusCaminhao($placa,$status);

        $i=0;
        for($i;$i<count($pecas); $i++){

            $dataSaida = date("Y-m-d");
            $inserir = $db->prepare("INSERT INTO saida_estoque (data_saida, qtd, peca_idpeca, placa, obs, servico, os, requisicao_saida, id_usuario) VALUES (:dataSaida, :qtd, :peca, :placa, :obs, :servico, :os, :requisicao_saida, :idUsuario)");
            $inserir->bindValue(':dataSaida', $dataSaida);
            $inserir->bindValue(':qtd', $qtd[$i]);
            $inserir->bindValue(':peca', $pecas[$i]);
            $inserir->bindValue(':placa', $placa);
            $inserir->bindValue(':obs', $obs);
            $inserir->bindValue(':servico', $servicos[$i]);
            $inserir->bindValue(':os', $idordemServico);
            $inserir->bindValue(':requisicao_saida', $requisicao[$i]);
            $inserir->bindValue(':idUsuario', $idUsuario);
            $inserir->execute();

            atualizaEStoque($pecas[$i]);

        }

        $db->commit();

        $_SESSION['msg'] = 'Saída e OS Atualizada com Sucesso';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar OS';
        $_SESSION['icon']='error';

        echo "Erro $e";
    }
    header("Location: ordem-servico.php");
    exit();   

}else{

}

?>