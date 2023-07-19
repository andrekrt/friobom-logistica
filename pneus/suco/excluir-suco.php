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

    $idSuco = filter_input(INPUT_GET, 'idsuco');


    $sql = $db->prepare("DELETE FROM sucos WHERE idsucos = :idSuco");
    $sql->bindValue(':idSuco', $idSuco);
   
    
    if($sql->execute()){
        echo "<script> alert('Suco Excluído!!')</script>";
        echo "<script> window.location.href='sucos.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>