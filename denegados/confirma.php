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

    $token = filter_input(INPUT_GET, 'token');

    $atualiza = $db->prepare("UPDATE denegadas SET situacao = :situacao WHERE token = :token");
    $atualiza->bindValue(':situacao', "Confirmado");
    $atualiza->bindValue(':token', $token);

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