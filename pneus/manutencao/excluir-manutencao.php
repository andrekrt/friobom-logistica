<?php

session_start();
require("../../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

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