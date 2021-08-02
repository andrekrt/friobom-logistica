<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99)){

    $id = filter_input(INPUT_GET, 'id');

    $delete = $db->prepare("DELETE FROM peca_reparo WHERE id_peca_reparo = :id ");
    $delete->bindValue(':id', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='pecas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>