<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $dataRodizio = date("Y-m-d");
    $pneu = filter_input(INPUT_POST, 'pneu');

    //consultas dados anteriores
    $consultaPneu=$db->prepare("SELECT * FROM pneus WHERE idpneus = :pneu");
    $consultaPneu->bindValue(':pneu', $pneu);
    $consultaPneu->execute();
    $dados = $consultaPneu->fetch();
    
    $veiculoAnterior = $dados['veiculo'];
    $kmInicialVeiculoAnterior = $dados['km_inicial'];
    $kmRodadoTotal = $dados['km_rodado'];
    $kmFinalVeiculoAnterior = filter_input(INPUT_POST, 'kmFinal');
    $kmRodadoAnterior = $kmFinalVeiculoAnterior-$kmInicialVeiculoAnterior;
    $novoVeiculo = filter_input(INPUT_POST, 'novoVeiculo');
    $kmInicialNovo = filter_input(INPUT_POST, 'kmInicial');
    $kmRodadoTotal = $kmRodadoTotal+$kmRodadoAnterior;
    $localizacao = filter_input(INPUT_POST, 'localizacao');
    $usuario = $_SESSION['idUsuario'];

    //echo "$dataRodizio<br>$pneu<br>$veiculoAnterior<br>$kmInicialVeiculoAnterior<br>$kmFinalVeiculoAnterior<br>$kmRodadoAnterior<br>$novoVeiculo<br>$kmInicialNovo";

    $sql = $db->prepare("INSERT INTO rodizio_pneu (data_rodizio, pneu, veiculo_anterior, km_inicial_veiculo_anterior, km_final_veiculo_anterior, km_rodado_veiculo_anterior, novo_veiculo, km_inicial_novo_veiculo, usuario) VALUES (:dataRodizio, :pneu, :veiculoAnterior, :kmInicialAnterior, :kmFinalAnterior, :kmRodadoAnterior, :novoVeiculo, :kmInicialNovo, :usuario)");
    $sql->bindValue(':dataRodizio', $dataRodizio);
    $sql->bindValue(':pneu', $pneu);
    $sql->bindValue(':veiculoAnterior', $veiculoAnterior);
    $sql->bindValue(':kmInicialAnterior', $kmInicialVeiculoAnterior);
    $sql->bindValue(':kmFinalAnterior', $kmFinalVeiculoAnterior);
    $sql->bindValue(':kmRodadoAnterior', $kmRodadoAnterior);
    $sql->bindValue(':novoVeiculo', $novoVeiculo);
    $sql->bindValue(':kmInicialNovo', $kmInicialNovo);
    $sql->bindValue(':usuario', $usuario);
    
    if($sql->execute()){
        $atualizaPneu = $db->prepare("UPDATE pneus SET veiculo = :novoVeiculo, km_inicial = :kmInicialNovo, km_rodado = :kmRodadoTotal, localizacao = :localizacao WHERE idpneus = :idpneu");
        $atualizaPneu->bindValue(':novoVeiculo', $novoVeiculo);
        $atualizaPneu->bindValue(':kmInicialNovo', $kmInicialNovo);
        $atualizaPneu->bindValue(':kmRodadoTotal', $kmRodadoTotal);
        $atualizaPneu->bindValue(':idpneu', $pneu);
        $atualizaPneu->bindValue(':localizacao', $localizacao );
        if($atualizaPneu->execute()){
            echo "<script> alert('Rodízio Lançado!!')</script>";
            echo "<script> window.location.href='rodizio.php' </script>";
        }else{
            print_r($atualizaPneu->errorInfo());
        }
        
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>