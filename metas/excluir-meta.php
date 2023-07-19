<?php

session_start();
require("../conexao.php");

$idModudulo = 15;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $token = filter_input(INPUT_GET, 'token');

    echo $token;

    $delete = $db->query("DELETE FROM metas WHERE token = '$token' ");

    if($delete){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='metas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>