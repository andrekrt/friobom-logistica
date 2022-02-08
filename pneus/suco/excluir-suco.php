<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

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