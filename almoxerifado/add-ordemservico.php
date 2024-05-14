<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');
include("funcoes.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idUsuario = $_SESSION['idUsuario'];
    $dataAbertura = date("Y-m-d H:i:s");
    $placa = filter_input(INPUT_POST, 'placa');
    $descricaoProblema = filter_input(INPUT_POST, 'problema');
    $corretiva = filter_input(INPUT_POST, 'corretiva')?1:0;
    $preventiva = filter_input(INPUT_POST, 'preventiva')?1:0;
    $externa = filter_input(INPUT_POST, 'externa')?1:0;
    $higienizacao = filter_input(INPUT_POST, 'higienizacao')?1:0;
    $causador = filter_input(INPUT_POST, 'causador')?filter_input(INPUT_POST, 'causador'):NULL;
    $numNf = filter_input(INPUT_POST, 'nf')?filter_input(INPUT_POST, 'nf'):null;
    $obs = filter_input(INPUT_POST, 'obs')?filter_input(INPUT_POST, 'obs'):NULL;
    $situacao ="Em Aberto";
    $servicos = $_POST['servico'];
    $pecas = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']);
    $numRequisicao = $_POST['requisicao'];
    $filial = $_SESSION['filial'];

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

        $inserir = $db->prepare("INSERT INTO ordem_servico (data_abertura, placa, descricao_problema, corretiva, preventiva, externa, higienizacao, causador, situacao, requisicao_saida, num_nf, obs, idusuario, filial) VALUES (:dataAbertura, :placa, :descricaoProblema, :corretiva, :preventiva, :externa, :higienizacao, :causador, :situacao, :requisicao, :numNf, :obs, :idusuario, :filial)");
        $inserir->bindValue(':dataAbertura', $dataAbertura);
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':descricaoProblema', $descricaoProblema);
        $inserir->bindValue(':corretiva', $corretiva);
        $inserir->bindValue(':preventiva', $preventiva);
        $inserir->bindValue(':externa', $externa);
        $inserir->bindValue(':higienizacao', $higienizacao);
        $inserir->bindValue(':causador', $causador);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':requisicao', $numRequisicao[0]);
        $inserir->bindValue(':numNf', $numNf);
        $inserir->bindValue(':obs', $obs);
        $inserir->bindValue(':idusuario', $idUsuario);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $idOs = $db->lastInsertId();
        if($externa===1){
            $status = "Manutenção Externa";
        }else{
            $status = "Manutenção Interna";
        }

        alterarStatusCaminhao($placa,$status);
        $i=0;
        for($i;$i<count($pecas); $i++){

            $dataSaida = date("Y-m-d");
            $inserir = $db->prepare("INSERT INTO saida_estoque (data_saida, qtd, peca_idpeca, placa, obs, servico, os, requisicao_saida, id_usuario, filial) VALUES (:dataSaida, :qtd, :peca, :placa, :obs, :servico, :os, :requisicao_saida, :idUsuario, :filial)");
            $inserir->bindValue(':dataSaida', $dataSaida);
            $inserir->bindValue(':qtd', $qtd[$i]);
            $inserir->bindValue(':peca', $pecas[$i]);
            $inserir->bindValue(':placa', $placa);
            $inserir->bindValue(':obs', $obs);
            $inserir->bindValue(':servico', $servicos[$i]);
            $inserir->bindValue(':os', $idOs);
            $inserir->bindValue(':requisicao_saida', $numRequisicao[$i]);
            $inserir->bindValue(':idUsuario', $idUsuario);
            $inserir->bindValue(':filial', $filial);
            $inserir->execute();

            atualizaEStoque($pecas[$i]);

            // $saidaPecas = addSaida($qtd[$i], $pecas[$i], $placa, $obs, $servicos[$i], $idOs, $idUsuario, $numRequisicao[$i]);
        }

        $db->commit();

        $_SESSION['msg'] = 'Saída Lançada e OS Registrada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Vale';
        $_SESSION['icon']='error';
    }    
    header("Location: ordem-servico.php");
    exit();
}

?>