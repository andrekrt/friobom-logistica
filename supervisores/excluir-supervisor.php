<?php

session_start();
require("../conexao.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $codigo = filter_input(INPUT_GET, 'codigo');

    $delete = $db->query("DELETE FROM supervisores WHERE idsupervisor = '$codigo' ");

    if($delete){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='supervisores.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>