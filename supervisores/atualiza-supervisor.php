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

    $codigo = filter_input(INPUT_POST, 'codigo');
    $nome = filter_input(INPUT_POST, 'nome');
    $cidade = filter_input(INPUT_POST, 'residencia');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    
    $sql = $db->prepare("UPDATE supervisores SET nome_supervisor=:supervisor, cidade_residencia = :cidade, veiculo=:veiculo WHERE idsupervisor=:codigo ");
    $sql->bindValue(':supervisor', $nome);
    $sql->bindValue(':codigo', $codigo);
    $sql->bindValue(':cidade', $cidade);
    $sql->bindValue(':veiculo', $veiculo);
    if($sql->execute()){
        echo "<script> alert('Supervisor Atualizado!!')</script>";
        echo "<script> window.location.href='supervisores.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }    

}else{
    echo "<script> alert('Acesso não permitido!!!')</script>";
    echo "<script> window.location.href='../index.php' </script>";
}

?>