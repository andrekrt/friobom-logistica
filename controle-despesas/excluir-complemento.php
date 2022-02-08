<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 4){

    $id = filter_input(INPUT_GET, 'idComplemento');

    $delete = $db->prepare("DELETE FROM complementos_combustivel WHERE id_complemento = :idComplemento ");
    $delete->bindValue(':idComplemento', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='complementos.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>