<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 99){

    $id = filter_input(INPUT_GET, 'idOcorrencia');

    $delete = $db->prepare("DELETE FROM ocorrencias WHERE idocorrencia = :idOcorrencia ");
    $delete->bindValue(':idOcorrencia', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='ocorrencias.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>