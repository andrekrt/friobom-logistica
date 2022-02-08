<?php

session_start();
require("../conexao.php");
include('funcoes.php');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idUsuario = $_SESSION['idUsuario'];

    $dataSaida = filter_input(INPUT_POST, 'dataSaida');
    $qtd = str_replace(",",".",filter_input(INPUT_POST, 'qtd')) ;
    $solicitante = filter_input(INPUT_POST, 'solicitante');
    $peca = filter_input(INPUT_POST, 'peca');
    $placa = filter_input(INPUT_POST, 'placa');
    $obs = filter_input(INPUT_POST, 'obs');  
    $totalEstoque = contaEstoque($peca);


    //echo "$qtdSaidas<br>$custo<br>$totalComprado<br>$totalEstoque<br>$novoEstoque";

    if($qtd<=$totalEstoque){

        $inserir = $db->prepare("INSERT INTO saida_estoque (data_saida, qtd, peca_idpeca, solicitante, placa, obs, id_usuario) VALUES (:dataSaida, :qtd, :peca, :solicitante, :placa, :obs, :idUsuario)");
        $inserir->bindValue(':dataSaida', $dataSaida);
        $inserir->bindValue(':qtd', $qtd);
        $inserir->bindValue(':peca', $peca);
        $inserir->bindValue(':solicitante', $solicitante);
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':obs', $obs);
        $inserir->bindValue(':idUsuario', $idUsuario);
 
        if($inserir->execute()){
            
            echo "<script>alert('Saída Lançada com Sucesso!');</script>";
            echo "<script>window.location.href='saidas.php'</script>";      
            
        }else{
            print_r($inserir->errorInfo());
        }

    }else{
        echo "<script>alert('Estoque Insuficiente');</script>";
        echo "<script>window.location.href='saidas.php'</script>";
    }

}

?>