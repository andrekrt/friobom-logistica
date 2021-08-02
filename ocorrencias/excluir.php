<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false  && ($_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 4 || $_SESSION['tipoUsuario'] == 99)){

    $id = filter_input(INPUT_GET, 'idLocal');

    $delete = $db->prepare("DELETE FROM local_reparo WHERE id_local = :idLocal ");
    $delete->bindValue(':idLocal', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='local-reparo.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>