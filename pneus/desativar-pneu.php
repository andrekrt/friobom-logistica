<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    $idPneu = filter_input(INPUT_POST, 'idpneu');
    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE idpneus = :idpneu");
    $consultaPneu->bindValue(':idpneu', $idPneu);
    $consultaPneu->execute();
    $dados = $consultaPneu->fetch();
    $kmInicial = $dados['km_inicial'];
    $kmFinal = filter_input(INPUT_POST, 'kmFinal');
    $kmRodado = $dados['km_rodado']+($kmFinal-$kmInicial);
    $uso = 0;

    //echo "$kmInicial<br>$kmFinal<br>$kmRodado";

    $sql = $db->prepare("UPDATE pneus SET km_final = :kmFinal, km_rodado = :kmRodado, uso = :uso WHERE idpneus = :idPneu");
    $sql->bindValue(':kmFinal', $kmFinal);
    $sql->bindValue(':kmRodado', $kmRodado);
    $sql->bindValue(':uso', $uso);
    $sql->bindValue(':idPneu', $idPneu);
    
    if($sql->execute()){
        echo "<script> alert('Pneu Desativado!!!')</script>";
        echo "<script> window.location.href='pneus.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>