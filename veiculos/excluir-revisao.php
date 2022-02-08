<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ) {

    $idRevisao = filter_input(INPUT_GET, 'idRevisao');

    $delete = $db->prepare("DELETE FROM revisao_veiculos WHERE id = :idRevisao");
    $delete->bindValue(':idRevisao', $idRevisao);
    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='revisao.php' </script>";
    }else{
        print_r($db->errorInfo());
    }
  
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
