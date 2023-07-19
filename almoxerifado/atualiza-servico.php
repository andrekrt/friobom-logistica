<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idservico = filter_input(INPUT_POST, 'id');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');

    $atualiza = $db->prepare("UPDATE servicos_almoxarifado SET descricao = :descricao, categoria = :categoria WHERE idservicos = :id");
    $atualiza->bindValue(':descricao', $descricao);
    $atualiza->bindValue(':categoria', $categoria);
    $atualiza->bindValue(':id', $idservico);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='servicos.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "<script> alert('Acesso não permitido')</script>";
    echo "<script> window.location.href='servicos.php' </script>";
}

?>