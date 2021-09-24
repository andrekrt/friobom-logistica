<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    $idSuco = filter_input(INPUT_POST, 'idsuco');
    $idpneu = filter_input(INPUT_POST, 'pneu');
    
    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE idpneus = :idpneu");
    $consultaPneu->bindValue(':idpneu', $idpneu);
    $consultaPneu->execute();
    $pneu = $consultaPneu->fetch();

    $kmVeiculo = filter_input(INPUT_POST, 'kmVeiculo');
    $carcaca = filter_input(INPUT_POST, 'carcaca');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST,'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');
    $calibragem = filter_input(INPUT_POST, 'calibragemAtual');    
    $kmPneu = $kmVeiculo-$pneu['km_inicial'];

    $sql = $db->prepare("UPDATE sucos SET km_veiculo = :kmVeiculo, km_pneu = :kmPneu, carcaca = :carcaca, suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04, calibragem = :calibragem, pneus_idpneus = :pneu WHERE idsucos = :idSuco");
    $sql->bindValue(':kmVeiculo', $kmVeiculo);
    $sql->bindValue(':kmPneu', $kmPneu);
    $sql->bindValue(':carcaca', $carcaca);
    $sql->bindValue(':suco01', $suco01);
    $sql->bindValue(':suco02', $suco02);
    $sql->bindValue(':suco03', $suco03);
    $sql->bindValue(':suco04', $suco04);
    $sql->bindValue(':calibragem', $calibragem);
    $sql->bindValue(':pneu', $idpneu);
    $sql->bindValue(':idSuco', $idSuco);
    
    if($sql->execute()){
        $atualizaPneu = $db->prepare("UPDATE pneus SET suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04 WHERE idpneus = :idpneu");
        $atualizaPneu->bindValue(':suco01', $suco01);
        $atualizaPneu->bindValue(':suco02', $suco02);
        $atualizaPneu->bindValue(':suco03', $suco03);
        $atualizaPneu->bindValue(':suco04', $suco04);
        $atualizaPneu->bindValue(':idpneu', $idpneu);

        if($atualizaPneu->execute()){
            echo "<script> alert('Suco Atualizado!!')</script>";
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