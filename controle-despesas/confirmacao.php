<?php

session_start();
require("../conexao.php");
include("../thermoking/funcao.php");

$idModudulo = 7;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $idUsuario = $_SESSION['idUsuario'];
    $idDespesa = filter_input(INPUT_GET, 'id');
    $status = "Confirmado";    
    $dataAprovaca = date("Y-m-d H:i");

    $sql = $db->prepare("UPDATE viagem SET situacao=:situacao, data_aprovacao=:dataAprovacao WHERE iddespesas = :idDespesa");
    $sql->bindValue(':situacao', $status);
    $sql->bindValue(':dataAprovacao', $dataAprovaca);
    $sql->bindValue(':idDespesa', $idDespesa );

    if($sql->execute()){
        echo "<script>alert('Confirmado!!!');</script>";
        echo "<script>window.location.href='despesas.php'</script>";
    }else{
        print_r($db->errorInfo());
    }
    

}else{
    header("Location: despesas.php");
}

?>