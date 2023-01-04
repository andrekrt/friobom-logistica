<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $dataMedicao = date("Y-m-d H:i:s");
    $idpneu = $_POST['idpneu'];
    $fogo = $_POST['fogo'];
    $kmInicialPneu = $_POST['kmPneu'];
    $kmVeiculo = $_POST['kmVeiculo'];
    $carcaca = $_POST['carcaca'];
    $suco01 = $_POST['suco01'];
    $suco02 = $_POST['suco02'];
    $suco03 = $_POST['suco03'];
    $suco04 = $_POST['suco04'];
    $calibragem = $_POST['calibragem'];
    $usuario = $_SESSION['idUsuario']; 
    $vida = $_POST['vida'];

    for($i=0; $i<count($idpneu);$i++){
        $kmPneu = $kmVeiculo-$kmInicialPneu[$i];

        //echo $suco01[$i]."<br>".$suco02[$i]."<br>".$suco03[$i]."<br>".$suco04[$i]."<br><br>";
        
        $sql = $db->prepare("INSERT INTO sucos (data_medicao, km_veiculo, km_pneu, carcaca, vida, suco01, suco02, suco03, suco04, calibragem, pneus_idpneus, usuario) VALUES (:dataMedida, :kmVeiculo, :kmPneu, :carcaca, :vida, :suco01, :suco02, :suco03, :suco04, :calibragem, :pneu, :usuario)");
        $sql->bindValue(':dataMedida', $dataMedicao);
        $sql->bindValue(':kmVeiculo', $kmVeiculo);
        $sql->bindValue(':kmPneu', $kmPneu);
        $sql->bindValue(':carcaca', $carcaca[$i]);
        $sql->bindValue(':vida', $vida[$i]);
        $sql->bindValue(':suco01', $suco01[$i]);
        $sql->bindValue(':suco02', $suco02[$i]);
        $sql->bindValue(':suco03', $suco03[$i]);
        $sql->bindValue(':suco04', $suco04[$i]);
        $sql->bindValue(':calibragem', $calibragem[$i]);
        $sql->bindValue(':pneu', $idpneu[$i]);
        $sql->bindValue(':usuario', $usuario);

        if($sql->execute()){
            echo "<script> alert('Sucos Registrado!!')</script>";
            echo "<script> window.location.href='form-suco.php' </script>";
            
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{
    echo "<script> alert('Acesso não permitido!!!')</script>";
    echo "<script> window.location.href='form-suco.php' </script>";
}

?>