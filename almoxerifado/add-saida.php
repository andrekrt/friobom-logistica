<?php

session_start();
require("../conexao.php");
include('funcoes.php');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idUsuario = $_SESSION['idUsuario'];

    $dataSaida = date("Y-m-d", strtotime(filter_input(INPUT_POST, 'dataAbertura'))) ;
    $qtd = str_replace(",",".",filter_input(INPUT_POST, 'qtd')) ;
    $placa = filter_input(INPUT_POST, 'placa');
    $obs = filter_input(INPUT_POST, 'obs');  
    $os = filter_input(INPUT_POST, 'idOrdemServico');

    $peca = $_POST['peca'];
    $servico = $_POST['servico'];
    $qtd = $_POST['qtd'];
    $requisicao = $_POST['requisicao'];

    // print_r($peca)."<br>";
    // print_r($servico)."<br>";
    // print_r($qtd)."<br>";
    // print_r($requisicao)."<br>";

    for($i=0; $i<count($qtd);$i++){
        if(contaEstoque($peca[$i])<$qtd[$i]){
            echo "<script>alert('estoque insuficiente')</script>";
            echo "<script>window.location.href='form-saidapeca-os.php?idOs=$os'</script>"; 
            exit;
        }
    }

    for($i=0;$i<count($peca);$i++){
        $inserir = $db->prepare("INSERT INTO saida_estoque (data_saida, qtd, peca_idpeca, placa, obs, servico, os, requisicao_saida, id_usuario) VALUES (:dataSaida, :qtd, :peca, :placa, :obs, :servico, :os, :requisicao, :idUsuario)");
        $inserir->bindValue(':dataSaida', $dataSaida);
        $inserir->bindValue(':qtd', $qtd[$i]);
        $inserir->bindValue(':peca', $peca[$i]);
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':obs', $obs);
        $inserir->bindValue(':servico', $servico[$i]);
        $inserir->bindValue(':os', $os);
        $inserir->bindValue(':requisicao', $requisicao[$i]);
        $inserir->bindValue(':idUsuario', $idUsuario);

        if($inserir->execute()){
            atualizaEStoque($peca[$i]);
            echo "<script>alert('Saída Lançada com Sucesso!');</script>";
            echo "<script>window.location.href='ordem-servico.php'</script>";      
            
        }else{
            print_r($inserir->errorInfo());
        }
    }
    
}

?>