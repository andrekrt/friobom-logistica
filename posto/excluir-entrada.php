<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 4 )){

    $id = filter_input(INPUT_GET, 'idEntrada');

    $delete = $db->prepare("DELETE FROM combustivel_entrada WHERE idcombustivel_entrada = :idEntrada ");
    $delete->bindValue(':idEntrada', $id);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='entradas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='entradas.php'</script>"; 
}

?>