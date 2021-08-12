<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==10 || $_SESSION['tipoUsuario'] == 99){

    $id = filter_input(INPUT_GET, 'idEntregas');

    $delete = $db->prepare("DELETE FROM entregas_capital WHERE identregas_capital = :idEntregas ");
    $delete->bindValue(':idEntregas', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='entregas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>