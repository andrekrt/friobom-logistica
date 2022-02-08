<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idpneu = filter_input(INPUT_POST, 'pneu');
    
    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE idpneus = :idpneu");
    $consultaPneu->bindValue(':idpneu', $idpneu);
    $consultaPneu->execute();
    $pneu = $consultaPneu->fetch();

    $dataMedicao = date("Y-m-d H:i:s");
    $kmVeiculo = filter_input(INPUT_POST, 'kmVeiculo');
    $carcaca = filter_input(INPUT_POST, 'carcaca');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST,'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');
    $calibragem = filter_input(INPUT_POST, 'calibragem');    
    $kmPneu = $kmVeiculo-$pneu['km_inicial'];
    $usuario = $_SESSION['idUsuario'];

    $sql = $db->prepare("INSERT INTO sucos (data_medicao, km_veiculo, km_pneu, carcaca, suco01, suco02, suco03, suco04, calibragem, pneus_idpneus, usuario) VALUES (:dataMedida, :kmVeiculo, :kmPneu, :carcaca, :suco01, :suco02, :suco03, :suco04, :calibragem, :pneu, :usuario)");
    $sql->bindValue(':dataMedida', $dataMedicao);
    $sql->bindValue(':kmVeiculo', $kmVeiculo);
    $sql->bindValue(':kmPneu', $kmPneu);
    $sql->bindValue(':carcaca', $carcaca);
    $sql->bindValue(':suco01', $suco01);
    $sql->bindValue(':suco02', $suco02);
    $sql->bindValue(':suco03', $suco03);
    $sql->bindValue(':suco04', $suco04);
    $sql->bindValue(':calibragem', $calibragem);
    $sql->bindValue(':pneu', $idpneu);
    $sql->bindValue(':usuario', $usuario);
    
    if($sql->execute()){
        $atualizaPneu = $db->prepare("UPDATE pneus SET suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04 WHERE idpneus = :idpneu");
        $atualizaPneu->bindValue(':suco01', $suco01);
        $atualizaPneu->bindValue(':suco02', $suco02);
        $atualizaPneu->bindValue(':suco03', $suco03);
        $atualizaPneu->bindValue(':suco04', $suco04);
        $atualizaPneu->bindValue(':idpneu', $idpneu);

        if($atualizaPneu->execute()){
            echo "<script> alert('Sucos Registrado!!')</script>";
            echo "<script> window.location.href='sucos.php' </script>";
        }else{
            print_r($atualizaPneu->errorInfo());
        }
        
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>