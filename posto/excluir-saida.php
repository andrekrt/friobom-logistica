<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){

    $id = filter_input(INPUT_GET, 'idSaida');

    $delete = $db->prepare("DELETE FROM combustivel_saida WHERE idcombustivel_saida = :idSaida ");
    $delete->bindValue(':idSaida', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='abastecimento.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>