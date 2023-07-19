<?php

session_start();
require("../conexao.php");

$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_POST,'id');
    $carga = filter_input(INPUT_POST, 'carga');
    $pedido = filter_input(INPUT_POST, 'pedido');
    $status = filter_input(INPUT_POST, 'status');    

    $atualiza = $db->prepare("UPDATE denegadas SET carga = :carga, pedido = :pedido, situacao = :situacao WHERE id_denegadas = :id");
    $atualiza->bindValue(':carga', $carga);
    $atualiza->bindValue(':pedido', $pedido);
    $atualiza->bindValue(':situacao', $status);
    $atualiza->bindValue(':id', $id);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='denegadas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>