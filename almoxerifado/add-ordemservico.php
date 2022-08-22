<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');
include("funcoes.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){
    
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

    // echo "Corretiva:$corretiva<br>";
    // echo "Preventiva:$preventiva<br>";
    // echo "Manutenção Externa: $externa<br>";
    // echo "Higienização: $higienizacao<br>";
    // echo "$idUsuario<br>$dataAbertura<br>$placa<br>$descricaoProblema<br>$causador<br>$numNf<br>$obs<br>$situacao<br>";
    // print_r($servicos)."<br>";
    // print_r($pecas)."<br>";
    // print_r($qtd)."<br>";
    // print_r($numRequisicao);
    $i=0;
    //echo count($qtd);
    for($i; $i<count($qtd);$i++){
        if(contaEstoque($pecas[$i])<$qtd[$i]){

            // echo "EStoque = ". contaEstoque($pecas[$i]);
            // echo "QTd = " . $qtd[$i];
            echo "<script>alert('estoque insuficiente')</script>";
            echo "<script>window.location.href='ordem-servico.php'</script>"; 
            exit;
        }
    }

    $inserir = $db->prepare("INSERT INTO ordem_servico (data_abertura, placa, descricao_problema, corretiva, preventiva, externa, higienizacao, causador, situacao, requisicao_saida, num_nf, obs, idusuario) VALUES (:dataAbertura, :placa, :descricaoProblema, :corretiva, :preventiva, :externa, :higienizacao, :causador, :situacao, :requisicao, :numNf, :obs, :idusuario)");
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

    if($inserir->execute()){
        $idOs = $db->lastInsertId();

        $i=0;
        for($i;$i<count($pecas); $i++){
            
            $saidaPecas = addSaida($qtd[$i], $pecas[$i], $placa, $obs, $servicos[$i], $idOs, $idUsuario, $numRequisicao[$i]);

            if($saidaPecas){
                //echo "ok";
                echo "<script>alert('Saída Lançada e OS Registrada!!!');</script>";
                
            }else{
                echo "erro na saida de peça";
            }
           
        }

        echo "<script>window.location.href='ordem-servico.php'</script>";

    }else{
        print_r($inserir->errorInfo());
    }

}

?>