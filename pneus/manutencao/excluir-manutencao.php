<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    $idManutencao = filter_input(INPUT_GET, 'idmanutencao');


    $sql = $db->prepare("DELETE FROM manutencao_pneu WHERE idmanutencao_pneu = :idManutencao");
    $sql->bindValue(':idManutencao', $idManutencao);
   
    
    if($sql->execute()){
        echo "<script> alert('Manutenção Excluída!!')</script>";
        echo "<script> window.location.href='manutencoes.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>