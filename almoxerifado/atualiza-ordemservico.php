<?php

session_start();
require("../conexao.php");
include("funcoes.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

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

    $i=0;
    for($i; $i<count($qtd);$i++){
        if(contaEstoque($pecas[$i])<$qtd[$i]){
            echo "<script>alert('estoque insuficiente')</script>";
            echo "<script>window.location.href='ordem-servico.php'</script>"; 
            exit;
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

    if($atualiza->execute()){
        $i=0;
        for($i;$i<count($pecas); $i++){
            
            $atualizaSaida = atualisaSaida($idSaida[$i], $servicos[$i], $pecas[$i], $qtd[$i], $requisicao[$i], $placa, $obs);

            if($atualizaSaida){
                //echo "ok";
                echo "<script>alert('Saída Lançada e OS Registrada!!!');</script>";
                echo "<script>window.location.href='ordem-servico.php'</script>";
            }else{
                echo "erro na saída de peças";
            }
           
        }
        
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>