<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){
    
    $idUsuario = $_SESSION['idUsuario'];
    $dataAbertura = date("Y-m-d H:i:s");
    $placa = filter_input(INPUT_POST, 'placa');
    $descricaoProblema = filter_input(INPUT_POST, 'problema');
    $corretiva = filter_input(INPUT_POST, 'corretiva')?1:0;
    $preventiva = filter_input(INPUT_POST, 'preventiva')?1:0;
    $externa = filter_input(INPUT_POST, 'externa')?1:0;
    $oleo = filter_input(INPUT_POST, 'oleo')?1:0;
    $higienizacao = filter_input(INPUT_POST, 'higienizacao')?1:0;
    $causador = filter_input(INPUT_POST, 'causador')?filter_input(INPUT_POST, 'causador'):NULL;
    $situacao ="Em Aberto";
    $requisicaoSaida = filter_input(INPUT_POST, 'requisicao')?filter_input(INPUT_POST, 'requisicao'):null;
    $solicitacaoPeca = filter_input(INPUT_POST, 'solicitacao')?filter_input(INPUT_POST, 'solicitacao'):null;
    $numNf = filter_input(INPUT_POST, 'numNf')?filter_input(INPUT_POST, 'numNf'):null;
    $obs = filter_input(INPUT_POST, 'obs')?filter_input(INPUT_POST, 'obs'):NULL;

    /*echo "Corretiva:$corretiva<br>";
    echo "Preventiva:$preventiva<br>";
    echo "Manutenção Externa: $externa<br>";
    echo "Troca de Óleo: $oleo<br>";
    echo "Higienização: $higienizacao<br>";*/
    //echo "$idUsuario<br>$dataAbertura<br>$placa<br>$descricaoProblema<br>$tipoManutencao<br>$causador<br>$situacao<br>$requisicaoSaida<br>$solicitacaoPeca<br>$numNf<br>$obs";

    $inserir = $db->prepare("INSERT INTO ordem_servico (data_abertura, placa, descricao_problema, corretiva, preventiva, externa, oleo, higienizacao, causador, situacao, requisicao_saida, solicitacao_peca, num_nf, obs, idusuario) VALUES (:dataAbertura, :placa, :descricaoProblema, :corretiva, :preventiva, :externa, :oleo, :higienizacao, :causador, :situacao, :requisicao, :solicitacao, :numNf, :obs, :idusuario)");
    $inserir->bindValue(':dataAbertura', $dataAbertura);
    $inserir->bindValue(':placa', $placa);
    $inserir->bindValue(':descricaoProblema', $descricaoProblema);
    $inserir->bindValue(':corretiva', $corretiva);
    $inserir->bindValue(':preventiva', $preventiva);
    $inserir->bindValue(':externa', $externa);
    $inserir->bindValue(':oleo', $oleo);
    $inserir->bindValue(':higienizacao', $higienizacao);
    $inserir->bindValue(':causador', $causador);
    $inserir->bindValue(':situacao', $situacao);
    $inserir->bindValue(':requisicao', $requisicaoSaida);
    $inserir->bindValue(':solicitacao', $solicitacaoPeca);
    $inserir->bindValue(':numNf', $numNf);
    $inserir->bindValue(':obs', $obs);
    $inserir->bindValue(':idusuario', $idUsuario);

    if($inserir->execute()){
        echo "<script>alert('Ordem de Serviço Lançada com Sucesso!');</script>";
        echo "<script>window.location.href='ordem-servico.php'</script>";
        
    }else{
        print_r($inserir->errorInfo());
    }

    

}

?>