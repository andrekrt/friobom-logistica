<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){
    
    $placa = filter_input(INPUT_POST,'placa');
    $kmAlinhamento = filter_input(INPUT_POST, 'kmAlinhamento');
    $dataAlinhamento = filter_input(INPUT_POST, 'dataAlinhamento');
    $tipoAlinhamento = filter_input(INPUT_POST, 'tipo');

    //echo "$placa<br>$kmAlinhamento<br>$dataAlinhamento<br>$tipoAlinhamento";

   $inserir = $db->prepare("INSERT INTO alinhamentos_veiculo (data_alinhamento, placa_veiculo, km_alinhamento, tipo_alinhamento) VALUES (:dataAlinhamento, :placa, :kmAlinhamento, :tipo)");
    $inserir->bindValue(':placa', $placa);
    $inserir->bindValue(':kmAlinhamento', $kmAlinhamento);
    $inserir->bindValue(':dataAlinhamento', $dataAlinhamento);
    $inserir->bindValue(':tipo', $tipoAlinhamento);    

    if($inserir->execute()){
        $atualizaVeiculo = $db->prepare("UPDATE veiculos SET km_alinhamento = :kmAlinhamento WHERE placa_veiculo = :placa");
        $atualizaVeiculo->bindValue(':kmAlinhamento', $kmAlinhamento);
        $atualizaVeiculo->bindValue(':placa',$placa);
        if($atualizaVeiculo->execute()){
            echo "<script>alert('Alinhamento Lançada!');</script>";
            echo "<script>window.location.href='alinhamentos.php'</script>";
        }else{
            print_r($atualizaVeiculo->errorInfo());
        }
        
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>