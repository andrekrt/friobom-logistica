<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 5 || $_SESSION['tipoUsuario'] == 6 || $_SESSION['tipoUsuario'] == 99){

    $id = filter_input(INPUT_GET, 'idCarregamento');

    $delete = $db->prepare("DELETE FROM carregamentos WHERE id = :idCarregamento ");
    $delete->bindValue(':idCarregamento', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='carregamentos.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>